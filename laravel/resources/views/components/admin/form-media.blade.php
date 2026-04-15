@props(['name', 'label', 'mediaAsset' => null, 'help' => '', 'tooltip' => '', 'croppable' => false])
<x-admin.media-picker :name="$name" :label="$label" :mediaAsset="$mediaAsset" :help="$help" :tooltip="$tooltip" :croppable="$croppable" />
