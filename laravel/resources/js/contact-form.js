document.addEventListener('alpine:init', () => {
    Alpine.data('contactForm', (formId, formSlug) => ({
        emailValue: '',
        emailVerified: false,
        showVerifyBtn: false,
        showOtpField: false,
        otpCode: '',
        otpMessage: '',
        formSubmitting: false,
        formMessage: '',
        formSuccess: false,
        requiresVerification: true,
        _formId: formId || 'contact-form',
        _formSlug: formSlug || 'contact-us',

        async checkEmail(email) {
            this.emailValue = email;
            if (!email || !email.includes('@')) return;

            try {
                const res = await fetch('/api/v1/otp/check', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? ''},
                    body: JSON.stringify({email})
                });
                if (!res.ok) throw new Error(res.status);
                const data = await res.json();
                if (data.verified) {
                    this.emailVerified = true;
                    this.showVerifyBtn = false;
                    this.showOtpField = false;
                } else {
                    this.showVerifyBtn = true;
                    this.emailVerified = false;
                }
            } catch {
                this.showVerifyBtn = true;
            }
        },

        async sendOtp() {
            this.showVerifyBtn = false;
            this.showOtpField = true;
            this.otpMessage = 'Sending code...';

            try {
                const res = await fetch('/api/v1/otp/send', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? ''},
                    body: JSON.stringify({email: this.emailValue})
                });
                if (!res.ok) throw new Error(res.status);
                const data = await res.json();
                this.otpMessage = data.message;
            } catch {
                this.otpMessage = 'Failed to send code. Please try again.';
                this.showVerifyBtn = true;
                this.showOtpField = false;
            }
        },

        async verifyOtp() {
            if (this.otpCode.length !== 6) {
                this.otpMessage = 'Please enter the 6-digit code.';
                return;
            }

            try {
                const res = await fetch('/api/v1/otp/verify', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? ''},
                    body: JSON.stringify({email: this.emailValue, otp: this.otpCode})
                });
                if (!res.ok) throw new Error(res.status);
                const data = await res.json();
                if (data.success) {
                    this.emailVerified = true;
                    this.showOtpField = false;
                    this.otpMessage = '';
                } else {
                    this.otpMessage = data.message;
                }
            } catch {
                this.otpMessage = 'Verification failed. Please try again.';
            }
        },

        async submitForm() {
            if (this.formSubmitting) return;
            this.formSubmitting = true;
            this.formMessage = '';

            const form = document.getElementById(this._formId);
            if (!form) { this.formMessage = 'Form not found.'; this.formSubmitting = false; return; }
            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => {
                if (Object.prototype.hasOwnProperty.call(data, key)) {
                    data[key] = Array.isArray(data[key]) ? [...data[key], value] : [data[key], value];
                } else {
                    data[key] = value;
                }
            });

            try {
                const res = await fetch(`/api/v1/forms/${this._formSlug}/submit`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? ''},
                    body: JSON.stringify(data)
                });
                if (!res.ok) throw new Error(res.status);
                const result = await res.json();
                this.formSuccess = result.success;
                this.formMessage = result.message || 'Your submission has been received.';
                if (result.success) form.reset();
            } catch {
                this.formSuccess = false;
                this.formMessage = 'Something went wrong. Please try again or call us directly.';
            } finally {
                this.formSubmitting = false;
            }
        }
    }));
});
