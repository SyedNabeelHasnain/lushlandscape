#!/usr/bin/env python3

from __future__ import annotations

import json
import re
import ssl
from collections import Counter
from datetime import datetime, timezone
from pathlib import Path
from typing import Dict, Iterable, List, Optional
from urllib.parse import urlparse
from urllib.request import Request, urlopen

ssl._create_default_https_context = ssl._create_unverified_context

ROOT = Path(__file__).resolve().parents[1]
CATALOG_PATH = ROOT / "storage/app/media-curated-catalog.json"
TEMPLATE_PATH = ROOT / "storage/app/media-dataset-template.json"

USER_AGENT = "Mozilla/5.0 (compatible; LushLandscapeCatalogBuilder/1.0)"
IMAGE_RE = re.compile(r"https?://[^\s)\]\"']+\.(?:jpg|jpeg|png|webp)(?:\?[^\s)\]\"']*)?", re.I)
BAD_URL_RE = re.compile(
    r"(?:logo|icon|flag|popup|badge|ribbon|brochure|catalog|map|search|"
    r"nav-|thumb|thumbnail|down-arrow|technical-resources|local-reps|"
    r"contractor-popup|get-a-free-estimate|3d-cover|layers_image|bg-decor|"
    r"shield|cta|banner|patch|customer-|meter|cover|bblocations|cartegoogle|"
    r"mega-menu|/menu/|hover|lamp-ready|integrated-led|complete-kits|expansion-kits|bt_menu|"
    r"90x90|150x150|160x160|300x300|63x24|126x58|10x9|404_|h=80&w=80|"
    r"width%3d100|height%3d100|width%3d160|height%3d160)",
    re.I,
)
MIME_ALLOW = {"image/jpeg", "image/png", "image/webp"}
MIN_BYTES = 16_000
MAX_POOL_CANDIDATES = 32

SERVICES = [
    {"slug": "interlocking-driveways", "name": "Interlocking Driveways"},
    {"slug": "interlocking-patios-backyard-living", "name": "Interlocking Patios & Backyard Living"},
    {"slug": "walkways-steps", "name": "Walkways & Steps"},
    {"slug": "natural-stone-flagstone", "name": "Natural Stone & Flagstone"},
    {"slug": "porcelain-pavers", "name": "Porcelain Pavers"},
    {"slug": "concrete-driveways", "name": "Concrete Driveways"},
    {"slug": "concrete-patios-walkways", "name": "Concrete Patios & Walkways"},
    {"slug": "interlock-restoration-sealing", "name": "Interlock Restoration & Sealing"},
    {"slug": "interlock-repair-lift-relay", "name": "Interlock Repair (Lift & Relay)"},
    {"slug": "retaining-walls", "name": "Retaining Walls"},
    {"slug": "sod-installation-grading", "name": "Sod Installation & Grading"},
    {"slug": "artificial-turf", "name": "Artificial Turf"},
    {"slug": "garden-design-planting", "name": "Garden Design & Planting"},
    {"slug": "landscape-lighting", "name": "Landscape Lighting"},
]

CITIES = [
    {"slug": "hamilton", "name": "Hamilton"},
    {"slug": "burlington", "name": "Burlington"},
    {"slug": "oakville", "name": "Oakville"},
    {"slug": "mississauga", "name": "Mississauga"},
    {"slug": "milton", "name": "Milton"},
    {"slug": "toronto", "name": "Toronto"},
    {"slug": "vaughan", "name": "Vaughan"},
    {"slug": "richmond-hill", "name": "Richmond Hill"},
    {"slug": "georgetown", "name": "Georgetown"},
    {"slug": "brampton", "name": "Brampton"},
]

CATEGORIES = [
    {
        "slug": "interlock-specialty-paving",
        "name": "Interlock & Specialty Paving",
        "pools": ["driveways", "patios", "walkways", "hardscape_general"],
    },
    {
        "slug": "concrete-services",
        "name": "Concrete Services",
        "pools": ["concrete", "hardscape_general"],
    },
    {
        "slug": "structural-hardscape-repair",
        "name": "Structural Hardscape & Repair",
        "pools": ["retaining", "maintenance", "hardscape_general"],
    },
    {
        "slug": "softscaping-lifestyle-enhancements",
        "name": "Softscaping & Lifestyle Enhancements",
        "pools": ["garden", "lighting", "lawn", "turf", "softscape_general"],
    },
]

STATIC_PAGES = [
    {"slug": "about", "title": "About Us", "pools": ["hardscape_general", "maintenance"]},
    {"slug": "process", "title": "Our Process", "pools": ["maintenance", "concrete", "lawn"]},
    {"slug": "warranty", "title": "Warranty & Maintenance", "pools": ["maintenance", "driveways", "retaining"]},
    {"slug": "financing", "title": "Financing", "pools": ["patios", "garden", "softscape_general"]},
    {"slug": "permits", "title": "Permits & Regulations", "pools": ["concrete", "retaining", "hardscape_general"]},
    {"slug": "awards", "title": "Awards & Certifications", "pools": ["permacon", "hardscape_general"]},
    {"slug": "reviews", "title": "Reviews", "pools": ["patios", "driveways", "hardscape_general"]},
    {"slug": "careers", "title": "Careers", "pools": ["maintenance", "driveways", "concrete"]},
    {"slug": "referral-program", "title": "Referral Program", "pools": ["patios", "garden", "softscape_general"]},
    {"slug": "privacy-policy", "title": "Privacy Policy", "pools": ["garden", "lighting", "softscape_general"]},
    {"slug": "terms", "title": "Terms & Conditions", "pools": ["retaining", "concrete", "hardscape_general"]},
]

SOURCE_PAGES: Dict[str, List[str]] = {
    "driveways": [
        "https://unilock.com/design-advice/driveways/",
        "https://www.techo-bloc.com/shop/pavers/hydra",
        "https://www.techo-bloc.com/shop/pavers/blu-80-mm-hd-6x13-smooth-onyx-black",
    ],
    "patios": [
        "https://unilock.com/design-advice/patios/",
        "https://unilock.com/design-advice/outdoor-living/",
        "https://unilock.com/outdoor-kitchens/",
        "https://unilock.com/design_advice/pool-deck/",
        "https://unilock.com/design-advice/firepits/",
        "https://unilock.com/articles/patio-design-outdoor-kitchen-long-island-ny/",
        "https://www.techo-bloc.com/shop/pavers/blu-80-mm-hd-6x13-smooth-onyx-black",
    ],
    "walkways": [
        "https://unilock.com/design-advice/walkways/",
        "https://unilock.com/design-advice/steps/",
    ],
    "retaining": [
        "https://unilock.com/design-advice/retaining-walls/",
        "https://unilock.com/design/retaining-wall-design-ideas/",
        "https://www.techo-bloc.com/shop/walls/raffinato-smooth",
        "https://www.techo-bloc.com/shop/walls/systema-wall",
        "https://permacon.ca/",
    ],
    "maintenance": [
        "https://unilock.com/maintenance/paver-cleaning/",
        "https://unilock.com/maintenance/fall-paver-maintenance-tips/",
        "https://unilock.com/maintenance/9-tips-for-paver-sealing-success/",
        "https://unilock.com/easyclean-2026/",
    ],
    "concrete": [
        "https://www.quikrete.com/patio/",
        "https://www.quikrete.com/athome/concreteresurfacer.asp",
        "https://www.quikrete.com/productlines/concreterepairpro.asp",
        "https://www.sakretecanada.com/en/sakrete-products/concrete-cement-masonry/concrete-mix/sakrete-concretemix.html",
        "https://www.sakretecanada.com/en/sakrete-products/concrete-masonryrepairproducts/concrete-repair-products/sakrete-top-n-bond.html",
    ],
    "garden": [
        "https://unilock.com/design-advice/garden-design/",
        "https://unilock.com/createspace-2026/",
        "https://unilock.com/learn-plan/design-considerations/",
        "https://unilock.com/design_advice/entrances/",
        "https://unilock.com/design-advice/water-feature/",
        "https://unilock.com/4-landscape-design-ideas-adding-outdoor-style-islip-ny-home/",
        "https://unilock.com/blog/sources-for-design-inspiration/",
        "https://www.provenwinners.com/learn/garden-design",
    ],
    "lighting": [
        "https://unilock.com/design-advice/outdoor-lighting/",
        "https://www.voltlighting.com/learn/avoid-landscape-lighting-mistakes",
    ],
    "lawn": [
        "https://www.pennington.com/all-products/grass-seed/resources/how-to-plant-grass-seed-in-a-new-lawn",
        "https://www.pennington.com/all-products/grass-seed/resources/how-to-grow-grass-fast",
        "https://stage.scotts.com/en-ca/how-to-grow/how-to-start-a-new-lawn-from-grass-seed.html",
    ],
    "turf": [
        "https://www.purchasegreen.com/solutions/lawn-landscapes",
        "https://www.purchasegreen.com/blog/advantages-of-installing-artificial-grass-in-your-backyard",
        "https://www.purchasegreen.com/solutions/pets",
        "https://www.purchasegreen.com/solutions/putting-greens",
        "https://www.purchasegreen.com/solutions/playgrounds",
    ],
    "permacon": [
        "https://permacon.ca/",
    ],
}

POOL_HINTS = {
    "driveways": ["driveway", "paver", "hydra", "blu"],
    "patios": ["patio", "backyard", "outdoor", "firepit", "blu"],
    "walkways": ["walkway", "steps", "path", "entry"],
    "retaining": ["wall", "retaining", "stone", "socius", "raffinato", "systema"],
    "maintenance": ["blog", "clean", "seal", "repair", "easyclean", "efflorescence"],
    "concrete": ["concrete", "resurfacer", "repair", "patio", "sakrete", "quikrete"],
    "garden": ["garden", "landscape", "scene", "plant", "flower"],
    "lighting": ["lighting", "light", "night", "volt", "ampiq"],
    "lawn": ["lawn", "grass", "seed", "soil", "tilling", "germinating"],
    "turf": ["turf", "grass", "backyard", "landscape", "lawn", "artificial"],
    "permacon": ["hdr", "mac", "am-", "st2022", "landscaping", "masonry"],
}

SERVICE_PLANS = {
    "interlocking-driveways": {"hero": ["driveways"], "gallery": ["driveways", "permacon"], "process": ["maintenance", "driveways"]},
    "interlocking-patios-backyard-living": {"hero": ["patios"], "gallery": ["patios", "garden", "lighting"], "process": ["maintenance", "patios"]},
    "walkways-steps": {"hero": ["walkways"], "gallery": ["walkways", "retaining", "permacon"], "process": ["maintenance", "walkways"]},
    "natural-stone-flagstone": {"hero": ["walkways", "retaining"], "gallery": ["walkways", "retaining", "permacon"], "process": ["maintenance", "retaining"]},
    "porcelain-pavers": {"hero": ["patios"], "gallery": ["patios", "driveways"], "process": ["maintenance", "patios"]},
    "concrete-driveways": {"hero": ["concrete"], "gallery": ["concrete", "hardscape_general"], "process": ["concrete"]},
    "concrete-patios-walkways": {"hero": ["concrete"], "gallery": ["concrete", "patios"], "process": ["concrete"]},
    "interlock-restoration-sealing": {"hero": ["maintenance"], "gallery": ["maintenance", "driveways"], "process": ["maintenance"]},
    "interlock-repair-lift-relay": {"hero": ["maintenance", "retaining"], "gallery": ["maintenance", "driveways"], "process": ["maintenance"]},
    "retaining-walls": {"hero": ["retaining"], "gallery": ["retaining", "permacon"], "process": ["retaining", "maintenance"]},
    "sod-installation-grading": {"hero": ["lawn", "garden"], "gallery": ["lawn", "garden", "softscape_general"], "process": ["lawn"]},
    "artificial-turf": {"hero": ["turf"], "gallery": ["turf", "softscape_general"], "process": ["turf", "lawn"]},
    "garden-design-planting": {"hero": ["garden"], "gallery": ["garden", "softscape_general"], "process": ["garden", "lawn"]},
    "landscape-lighting": {"hero": ["lighting"], "gallery": ["lighting", "garden"], "process": ["lighting", "garden"]},
}


def mirror_url(page_url: str) -> str:
    normalized = page_url.removeprefix("https://").removeprefix("http://")
    return f"https://r.jina.ai/http://{normalized}"


def http_get(url: str, timeout: int = 20) -> bytes:
    request = Request(url, headers={"User-Agent": USER_AGENT})
    with urlopen(request, timeout=timeout) as response:
        return response.read()


def fetch_page_text(page_url: str) -> str:
    return http_get(mirror_url(page_url)).decode("utf-8", "ignore")


def domain_credit(page_url: str) -> str:
    host = urlparse(page_url).netloc.lower()
    if "unilock" in host:
        return "Image from Unilock"
    if "techo-bloc" in host:
        return "Image from Techo-Bloc"
    if "permacon" in host:
        return "Image from Permacon"
    if "quikrete" in host:
        return "Image from QUIKRETE"
    if "sakrete" in host:
        return "Image from SAKRETE"
    if "provenwinners" in host:
        return "Image from Proven Winners"
    if "voltlighting" in host:
        return "Image from VOLT Lighting"
    if "pennington" in host:
        return "Image from Pennington"
    if "purchasegreen" in host:
        return "Image from Purchase Green"

    return f"Image from {host}"


def image_domain(url: str) -> str:
    return urlparse(url).netloc.lower()


def valid_image_url(url: str) -> bool:
    return url.lower().startswith("http") and not BAD_URL_RE.search(url)


def verify_image(url: str, cache: Dict[str, Optional[dict]]) -> Optional[dict]:
    if url in cache:
        return cache[url]

    try:
        body = http_get(url, timeout=25)
        if len(body) < MIN_BYTES:
            cache[url] = None
            return None

        mime = sniff_mime(body)
        if mime not in MIME_ALLOW:
            cache[url] = None
            return None

        cache[url] = {
            "mime_type": mime,
            "file_size": len(body),
            "orientation": "landscape",
        }
        return cache[url]
    except Exception:
        cache[url] = None
        return None


def sniff_mime(body: bytes) -> Optional[str]:
    if body.startswith(b"\x89PNG\r\n\x1a\n"):
        return "image/png"
    if body.startswith(b"\xff\xd8"):
        return "image/jpeg"
    if body.startswith(b"RIFF") and body[8:12] == b"WEBP":
        return "image/webp"
    return None


def extract_candidates() -> Dict[str, List[dict]]:
    verify_cache: Dict[str, Optional[dict]] = {}
    pools: Dict[str, List[dict]] = {}

    for pool_name, pages in SOURCE_PAGES.items():
        seen = set()
        raw_candidates: List[dict] = []

        print(f"Collecting pool: {pool_name}")

        for page in pages:
            try:
                text = fetch_page_text(page)
            except Exception:
                continue

            for raw_url in IMAGE_RE.findall(text):
                url = raw_url.replace("http://", "https://")
                if not valid_image_url(url) or url in seen:
                    continue

                seen.add(url)
                raw_candidates.append(
                    {
                        "url": url,
                        "credit": domain_credit(page),
                        "source_page": page,
                        "source_domain": image_domain(url),
                        "score": candidate_score(pool_name, url),
                    }
                )

        raw_candidates.sort(key=lambda item: item["score"], reverse=True)
        pool_items: List[dict] = []

        for candidate in raw_candidates[:MAX_POOL_CANDIDATES]:
            verified = verify_image(candidate["url"], verify_cache)
            if not verified:
                continue

            pool_items.append(
                {
                    "url": candidate["url"],
                    "credit": candidate["credit"],
                    "source_page": candidate["source_page"],
                    "source_domain": candidate["source_domain"],
                    **verified,
                }
            )

        print(f"  verified: {len(pool_items)}")
        pools[pool_name] = pool_items

    pools["hardscape_general"] = unique_merge(
        pools.get("driveways", []),
        pools.get("patios", []),
        pools.get("walkways", []),
        pools.get("retaining", []),
        pools.get("permacon", []),
    )
    pools["softscape_general"] = unique_merge(
        pools.get("garden", []),
        pools.get("lighting", []),
        pools.get("lawn", []),
        pools.get("turf", []),
    )
    pools["city_general"] = unique_merge(
        pools.get("hardscape_general", []),
        pools.get("softscape_general", []),
    )

    return pools


def candidate_score(pool_name: str, url: str) -> int:
    lowered = url.lower()
    score = 0

    for token in POOL_HINTS.get(pool_name, []):
        if token in lowered:
            score += 10

    for token in ["project", "gallery", "blog", "landscape", "backyard", "front", "scene", "patio", "driveway", "wall"]:
        if token in lowered:
            score += 4

    for token in ["1024", "1080", "1200", "2043", "928", "902", "800"]:
        if token in lowered:
            score += 2

    if "houzz.com/imageclipperupload" in lowered:
        score -= 40

    return score


def unique_merge(*groups: Iterable[dict]) -> List[dict]:
    merged: List[dict] = []
    seen = set()
    for group in groups:
        for item in group:
            if item["url"] in seen:
                continue
            seen.add(item["url"])
            merged.append(item)
    return merged


def pick_from_pools(
    pools: Dict[str, List[dict]],
    used_urls: set[str],
    pool_names: List[str],
    count: int,
) -> List[dict]:
    hardscape_pools = {"driveways", "patios", "walkways", "retaining", "maintenance", "concrete", "permacon", "hardscape_general"}
    softscape_pools = {"garden", "lighting", "lawn", "turf", "softscape_general"}
    combined_pools = list(pool_names)

    if any(name in hardscape_pools for name in pool_names):
        combined_pools.extend(["hardscape_general"])

    if any(name in softscape_pools for name in pool_names):
        combined_pools.extend(["softscape_general"])

    combined_pools.extend(["city_general"])
    combined_pools = list(dict.fromkeys(combined_pools))

    picked: List[dict] = []

    while len(picked) < count:
        progress = False

        for pool_name in combined_pools:
            for candidate in pools.get(pool_name, []):
                if candidate["url"] in used_urls:
                    continue

                picked.append(candidate)
                used_urls.add(candidate["url"])
                progress = True
                break

            if len(picked) >= count:
                break

        if not progress:
            break

    return picked


def build_items() -> List[dict]:
    pools = extract_candidates()
    used_urls: set[str] = set()
    items: List[dict] = []

    def add_item(base: dict, candidate: dict) -> None:
        items.append(
            {
                **base,
                "url": candidate["url"],
                "credit": candidate["credit"],
                "source_page": candidate["source_page"],
                "source_domain": candidate["source_domain"],
                "mime_type": candidate["mime_type"],
                "file_size": candidate["file_size"],
                "orientation": candidate["orientation"],
            }
        )

    for service in SERVICES:
        plan = SERVICE_PLANS[service["slug"]]

        hero_pick = pick_from_pools(pools, used_urls, plan["hero"], 1)
        for candidate in hero_pick:
            add_item(
                {
                    "internal_title": f"{service['name']} - Hero",
                    "description": f"Curated hero image for {service['name']}.",
                    "default_alt_text": f"{service['name']} project inspiration",
                    "image_purpose": "informative",
                    "location_city": None,
                    "tags": [service["slug"], "hero", "service"],
                    "keywords": [service["name"].lower(), "hero"],
                    "placement": "service_hero",
                    "service_slug": service["slug"],
                },
                candidate,
            )

        gallery_picks = pick_from_pools(pools, used_urls, plan["gallery"], 4)
        for index, candidate in enumerate(gallery_picks, start=1):
            add_item(
                {
                    "internal_title": f"{service['name']} - Gallery {index}",
                    "description": f"Curated gallery image {index} for {service['name']}.",
                    "default_alt_text": f"{service['name']} gallery image {index}",
                    "image_purpose": "informative",
                    "location_city": None,
                    "tags": [service["slug"], "gallery", f"gallery-{index}"],
                    "keywords": [service["name"].lower(), "gallery"],
                    "placement": "service_gallery",
                    "service_slug": service["slug"],
                    "gallery_index": index,
                },
                candidate,
            )

        process_picks = pick_from_pools(pools, used_urls, plan["process"], 4)
        for index, candidate in enumerate(process_picks, start=1):
            add_item(
                {
                    "internal_title": f"{service['name']} - Process Step {index}",
                    "description": f"Curated process image {index} for {service['name']}.",
                    "default_alt_text": f"{service['name']} process step {index}",
                    "image_purpose": "informative",
                    "location_city": None,
                    "tags": [service["slug"], "process", f"step-{index}"],
                    "keywords": [service["name"].lower(), "process"],
                    "placement": "service_process",
                    "service_slug": service["slug"],
                    "step_number": index,
                },
                candidate,
            )

    home_pick = pick_from_pools(pools, used_urls, ["hardscape_general", "softscape_general"], 1)
    if home_pick:
        add_item(
            {
                "internal_title": "Homepage Hero - Lush Landscape",
                "description": "Curated homepage hero image for Lush Landscape sourced from approved official media.",
                "default_alt_text": "Premium landscaping and hardscaping project by Lush Landscape",
                "image_purpose": "informative",
                "location_city": None,
                "tags": ["homepage", "hero", "curated"],
                "keywords": ["homepage", "landscaping", "hardscaping", "outdoor living"],
                "placement": "home_hero",
            },
            home_pick[0],
        )

    for category in CATEGORIES:
        pick = pick_from_pools(pools, used_urls, category["pools"], 1)
        if not pick:
            continue

        add_item(
            {
                "internal_title": f"{category['name']} - Category Hero",
                "description": f"Curated category hero image for {category['name']}.",
                "default_alt_text": f"{category['name']} project inspiration",
                "image_purpose": "informative",
                "location_city": None,
                "tags": ["category", "hero", category["slug"]],
                "keywords": [category["name"].lower(), "category hero"],
                "placement": "category_hero",
                "category_slug": category["slug"],
            },
            pick[0],
        )

    for city in CITIES:
        pick = pick_from_pools(pools, used_urls, ["city_general"], 1)
        if not pick:
            continue

        add_item(
            {
                "internal_title": f"{city['name']} - City Hero",
                "description": f"Curated city page hero image for {city['name']}.",
                "default_alt_text": f"Landscape project inspiration for {city['name']}, Ontario",
                "image_purpose": "informative",
                "location_city": city["name"],
                "tags": ["city", "hero", city["slug"]],
                "keywords": [city["name"].lower(), "city hero", "ontario landscaping"],
                "placement": "city_hero",
                "city_slug": city["slug"],
            },
            pick[0],
        )

    for page in STATIC_PAGES:
        pick = pick_from_pools(pools, used_urls, page["pools"], 1)
        if not pick:
            continue

        add_item(
            {
                "internal_title": f"{page['title']} - Page Hero",
                "description": f"Curated static-page hero image for {page['title']}.",
                "default_alt_text": f"{page['title']} page hero image",
                "image_purpose": "informative",
                "location_city": None,
                "tags": ["static-page", "hero", page["slug"]],
                "keywords": [page["title"].lower(), "page hero"],
                "placement": "static_hero",
                "page_slug": page["slug"],
            },
            pick[0],
        )

    return items


def build_dataset(items: List[dict]) -> dict:
    summary = Counter(item["placement"] for item in items)
    return {
        "generated_at": datetime.now(timezone.utc).isoformat(),
        "strategy": "official_partner_catalog",
        "total": len(items),
        "summary": dict(summary),
        "items": items,
    }


def main() -> None:
    items = build_items()
    dataset = build_dataset(items)

    CATALOG_PATH.write_text(json.dumps(dataset, indent=2, ensure_ascii=True), encoding="utf-8")
    TEMPLATE_PATH.write_text(json.dumps(dataset, indent=2, ensure_ascii=True), encoding="utf-8")

    print(f"Wrote {dataset['total']} curated items to {CATALOG_PATH}")
    print("Summary:")
    for placement, count in sorted(dataset["summary"].items()):
        print(f"  {placement}: {count}")


if __name__ == "__main__":
    main()
