<x-layouts::app
  title="Demande de devis site internet à Cotonou | Primous"
  description="Décrivez votre projet de site internet en 5 questions et recevez une proposition adaptée à votre activité. Création de sites à Cotonou, échange gratuit."
>
@php
    $phone = config('site.phone');
    $telUrl = 'tel:' . preg_replace('/[^0-9+]/', '', (string) $phone);
    $whatsapp = config('site.whatsapp');
    $whatsappClean = preg_replace('/[^0-9]/', '', (string)$whatsapp);
    $whatsappMessage = config('site.whatsapp_message');
    $whatsappUrl = 'https://wa.me/' . $whatsappClean . '?text=' . urlencode($whatsappMessage);
@endphp

<x-layout.section bg="white">
    <div id="demande-de-projet" class="max-w-[1140px] mx-auto scroll-mt-24">
        
        <div 
            x-data="{
                step: 1,
                formData: {
                    activity: '',
                    has_website: '',
                    difficulties: [],
                    main_difficulty: '',
                    objective: '',
                    need: '',
                    budget: '',
                    budget_unknown: false,
                    full_name: '',
                    email: '',
                    phone: '',
                    consent: false
                },
                budgetOption: '',
                customBudget: '',
                errors: {},
                honeypot: '',
                sending: false,
                submitted: false,
                submitError: false,

                selectBudget(option) {
                    this.budgetOption = option;
                    this.formData.budget_unknown = false;
                    if (option === 'custom') {
                        this.formData.budget = this.customBudget;
                    } else {
                        this.formData.budget = option;
                    }
                    delete this.errors.budget;
                },

                updateCustomBudget(val) {
                    this.customBudget = val;
                    if (this.budgetOption === 'custom') {
                        this.formData.budget = val;
                        if (val.trim()) {
                            delete this.errors.budget;
                        }
                    }
                },

                toggleBudgetUnknown() {
                    if (this.formData.budget_unknown) {
                        this.budgetOption = '';
                        this.formData.budget = '';
                        this.customBudget = '';
                        delete this.errors.budget;
                    }
                },

                validateStep(currentStep) {
                    this.errors = {};

                    if (currentStep === 1) {
                        if (!this.formData.activity.trim()) {
                            this.errors.activity = 'Décrivez votre activité en quelques mots pour continuer.';
                        }
                        if (!this.formData.has_website) {
                            this.errors.has_website = 'Choisissez une réponse pour continuer.';
                        }
                    }

                    if (currentStep === 2) {
                        if (this.formData.difficulties.length === 0) {
                            this.errors.difficulties = 'Cochez au moins une difficulté pour continuer.';
                        }
                    }

                    if (currentStep === 3) {
                        if (!this.formData.objective.trim()) {
                            this.errors.objective = 'Indiquez ce que vous attendez de votre site pour continuer.';
                        }
                        if (!this.formData.need.trim()) {
                            this.errors.need = 'Décrivez votre besoin en quelques lignes pour continuer.';
                        }
                    }

                    if (currentStep === 4) {
                        if (!this.formData.budget_unknown) {
                            if (!this.budgetOption) {
                                this.errors.budget = 'Sélectionnez une option de budget ou cochez « Je ne sais pas encore ».';
                            } else if (this.budgetOption === 'custom' && !this.formData.budget.trim()) {
                                this.errors.budget = 'Veuillez préciser le montant de votre budget.';
                            }
                        }
                    }

                    if (currentStep === 5) {
                        if (!this.formData.full_name.trim()) {
                            this.errors.full_name = 'Entrez votre nom complet.';
                        }
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!this.formData.email.trim() || !emailRegex.test(this.formData.email)) {
                            this.errors.email = 'Entrez une adresse email valide.';
                        }
                        if (!this.formData.phone.trim()) {
                            this.errors.phone = 'Entrez un numéro valide, avec l’indicatif du pays.';
                        }
                        if (!this.formData.consent) {
                            this.errors.consent = 'Cochez cette case pour envoyer votre demande.';
                        }
                    }

                    return Object.keys(this.errors).length === 0;
                },

                nextStep() {
                    if (this.validateStep(this.step)) {
                        if (this.step < 5) {
                            this.step++;
                            this.scrollToForm();
                        }
                    }
                },

                prevStep() {
                    if (this.step > 1) {
                        this.step--;
                        this.errors = {};
                        this.scrollToForm();
                    }
                },

                scrollToForm() {
                    document.getElementById('demande-de-projet')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                },

                async submitForm() {
                    if (!this.validateStep(5)) {
                        return;
                    }

                    this.sending = true;
                    this.submitError = false;

                    try {
                        const csrfToken = document.querySelector('meta[name=csrf-token]')?.getAttribute('content');
                        const response = await fetch('{{ route('demande-de-projet.store') }}', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken || ''
                            },
                            body: JSON.stringify({
                                ...this.formData,
                                website: this.honeypot
                            })
                        });

                        if (response.status === 201) {
                            this.submitted = true;
                            this.submitError = false;
                            this.scrollToForm();
                        } else {
                            this.submitted = false;
                            this.submitError = true;
                        }
                    } catch (error) {
                        this.submitted = false;
                        this.submitError = true;
                    } finally {
                        this.sending = false;
                    }
                }
            }"
            class="rounded-[28px] sm:rounded-[36px] md:rounded-[44px] bg-ink-soft p-4 sm:p-6 md:p-8 lg:p-10 shadow-2xl relative overflow-hidden text-white"
        >

            <!-- Filigranes décoratifs en arrière-plan -->
            <div class="absolute -left-16 -top-16 w-80 h-80 rounded-full border-[30px] border-white/5 pointer-events-none" aria-hidden="true"></div>
            <div class="absolute left-32 bottom-0 w-96 h-96 rounded-full border-[40px] border-white/5 pointer-events-none" aria-hidden="true"></div>

            <!-- Grille 2 Volets -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-stretch relative z-10">
                
                <!-- Volet gauche (5 colonnes) : Titre H1 & Présentation -->
                <div class="lg:col-span-5 flex flex-col justify-between py-4 lg:py-6 space-y-8">
                    <div class="space-y-4">
                        <span class="inline-block px-3.5 py-1 rounded-full bg-white/15 backdrop-blur-md text-white font-body text-xs font-semibold">
                            Échange gratuit et sans engagement
                        </span>

                        <h1 class="font-heading font-extrabold text-[28px] sm:text-[36px] md:text-[42px] text-white leading-[1.12] tracking-tight">
                            Parlez-moi de votre projet
                        </h1>

                        <p class="font-body text-[15px] sm:text-base text-white/85 leading-relaxed pt-1">
                            Cinq questions simples pour que je comprenne votre activité et ce que vous voulez construire. Gratuit et sans engagement.
                        </p>
                    </div>

                    <!-- Barre de progression Stepper (Inspirée de l'image 3) -->
                    <div class="space-y-3 pt-5 border-t border-white/15">
                        <div class="flex items-center justify-between">
                            <p class="font-heading font-semibold text-xs tracking-wider uppercase text-white/80">
                                Progression
                            </p>
                            <span class="font-body text-xs font-bold px-2.5 py-0.5 rounded-full bg-white/20 text-white backdrop-blur-sm" x-text="'Étape ' + step + ' sur 5'">
                                Étape 1 sur 5
                            </span>
                        </div>

                        <!-- Stepper avec ligne connectée et jalons -->
                        <div class="relative w-full pt-1 pb-2">
                            <!-- Ligne de fond inactive (connecte les centres des cercles de 10% à 90%) -->
                            <div class="absolute top-[24px] sm:top-[26px] left-[10%] right-[10%] h-0.5 bg-white/25 rounded-full -z-0" aria-hidden="true"></div>

                            <!-- Ligne de progression active (anime de 0% à 80%) -->
                            <div 
                                class="absolute top-[24px] sm:top-[26px] left-[10%] h-0.5 bg-white rounded-full transition-all duration-300 ease-out -z-0"
                                :style="'width: ' + ((step - 1) / 4 * 80) + '%'"
                                aria-hidden="true"
                            ></div>

                            <!-- Les 5 étapes -->
                            <div class="relative z-10 flex items-start justify-between">
                                
                                <!-- Étape 1 : Activité -->
                                <div class="w-1/5 flex flex-col items-center text-center">
                                    <div class="h-2.5 flex items-center justify-center">
                                        <svg x-show="step === 1" x-cloak viewBox="0 0 10 6" class="size-2 text-white fill-white">
                                            <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <button 
                                        type="button"
                                        @click="if (step > 1) { step = 1; scrollToForm(); }"
                                        class="size-7 sm:size-8 rounded-full flex items-center justify-center transition-all duration-200 select-none"
                                        :class="step === 1 
                                            ? 'bg-white text-primary shadow-lg ring-4 ring-white/30 font-extrabold' 
                                            : (step > 1 
                                                ? 'bg-white text-primary shadow font-bold cursor-pointer hover:scale-105' 
                                                : 'border-2 border-white/40 bg-white/10 text-white/50 font-medium cursor-default')"
                                        aria-label="Étape 1 : Activité"
                                    >
                                        <svg x-show="step > 1" class="size-3.5 sm:size-4 stroke-[3]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                        <span x-show="step <= 1" class="text-xs sm:text-sm">1</span>
                                    </button>
                                    <span 
                                        class="mt-1.5 text-[10px] sm:text-xs tracking-tight transition-colors"
                                        :class="step === 1 ? 'text-white font-bold' : (step > 1 ? 'text-white/90 font-medium' : 'text-white/50')"
                                    >
                                        Activité
                                    </span>
                                </div>

                                <!-- Étape 2 : Difficultés -->
                                <div class="w-1/5 flex flex-col items-center text-center">
                                    <div class="h-2.5 flex items-center justify-center">
                                        <svg x-show="step === 2" x-cloak viewBox="0 0 10 6" class="size-2 text-white fill-white">
                                            <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <button 
                                        type="button"
                                        @click="if (step > 2) { step = 2; scrollToForm(); }"
                                        class="size-7 sm:size-8 rounded-full flex items-center justify-center transition-all duration-200 select-none"
                                        :class="step === 2 
                                            ? 'bg-white text-primary shadow-lg ring-4 ring-white/30 font-extrabold' 
                                            : (step > 2 
                                                ? 'bg-white text-primary shadow font-bold cursor-pointer hover:scale-105' 
                                                : 'border-2 border-white/40 bg-white/10 text-white/50 font-medium cursor-default')"
                                        aria-label="Étape 2 : Difficultés"
                                    >
                                        <svg x-show="step > 2" class="size-3.5 sm:size-4 stroke-[3]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                        <span x-show="step <= 2" class="text-xs sm:text-sm">2</span>
                                    </button>
                                    <span 
                                        class="mt-1.5 text-[10px] sm:text-xs tracking-tight transition-colors"
                                        :class="step === 2 ? 'text-white font-bold' : (step > 2 ? 'text-white/90 font-medium' : 'text-white/50')"
                                    >
                                        Difficultés
                                    </span>
                                </div>

                                <!-- Étape 3 : Attentes -->
                                <div class="w-1/5 flex flex-col items-center text-center">
                                    <div class="h-2.5 flex items-center justify-center">
                                        <svg x-show="step === 3" x-cloak viewBox="0 0 10 6" class="size-2 text-white fill-white">
                                            <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <button 
                                        type="button"
                                        @click="if (step > 3) { step = 3; scrollToForm(); }"
                                        class="size-7 sm:size-8 rounded-full flex items-center justify-center transition-all duration-200 select-none"
                                        :class="step === 3 
                                            ? 'bg-white text-primary shadow-lg ring-4 ring-white/30 font-extrabold' 
                                            : (step > 3 
                                                ? 'bg-white text-primary shadow font-bold cursor-pointer hover:scale-105' 
                                                : 'border-2 border-white/40 bg-white/10 text-white/50 font-medium cursor-default')"
                                        aria-label="Étape 3 : Attentes"
                                    >
                                        <svg x-show="step > 3" class="size-3.5 sm:size-4 stroke-[3]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                        <span x-show="step <= 3" class="text-xs sm:text-sm">3</span>
                                    </button>
                                    <span 
                                        class="mt-1.5 text-[10px] sm:text-xs tracking-tight transition-colors"
                                        :class="step === 3 ? 'text-white font-bold' : (step > 3 ? 'text-white/90 font-medium' : 'text-white/50')"
                                    >
                                        Attentes
                                    </span>
                                </div>

                                <!-- Étape 4 : Budget -->
                                <div class="w-1/5 flex flex-col items-center text-center">
                                    <div class="h-2.5 flex items-center justify-center">
                                        <svg x-show="step === 4" x-cloak viewBox="0 0 10 6" class="size-2 text-white fill-white">
                                            <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <button 
                                        type="button"
                                        @click="if (step > 4) { step = 4; scrollToForm(); }"
                                        class="size-7 sm:size-8 rounded-full flex items-center justify-center transition-all duration-200 select-none"
                                        :class="step === 4 
                                            ? 'bg-white text-primary shadow-lg ring-4 ring-white/30 font-extrabold' 
                                            : (step > 4 
                                                ? 'bg-white text-primary shadow font-bold cursor-pointer hover:scale-105' 
                                                : 'border-2 border-white/40 bg-white/10 text-white/50 font-medium cursor-default')"
                                        aria-label="Étape 4 : Budget"
                                    >
                                        <svg x-show="step > 4" class="size-3.5 sm:size-4 stroke-[3]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                        <span x-show="step <= 4" class="text-xs sm:text-sm">4</span>
                                    </button>
                                    <span 
                                        class="mt-1.5 text-[10px] sm:text-xs tracking-tight transition-colors"
                                        :class="step === 4 ? 'text-white font-bold' : (step > 4 ? 'text-white/90 font-medium' : 'text-white/50')"
                                    >
                                        Budget
                                    </span>
                                </div>

                                <!-- Étape 5 : Contact -->
                                <div class="w-1/5 flex flex-col items-center text-center">
                                    <div class="h-2.5 flex items-center justify-center">
                                        <svg x-show="step === 5" x-cloak viewBox="0 0 10 6" class="size-2 text-white fill-white">
                                            <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <button 
                                        type="button"
                                        class="size-7 sm:size-8 rounded-full flex items-center justify-center transition-all duration-200 select-none"
                                        :class="step === 5 
                                            ? 'bg-white text-primary shadow-lg ring-4 ring-white/30 font-extrabold cursor-default' 
                                            : 'border-2 border-white/40 bg-white/10 text-white/50 font-medium cursor-default'"
                                        aria-label="Étape 5 : Contact"
                                    >
                                        <span class="text-xs sm:text-sm">5</span>
                                    </button>
                                    <span 
                                        class="mt-1.5 text-[10px] sm:text-xs tracking-tight transition-colors"
                                        :class="step === 5 ? 'text-white font-bold' : 'text-white/50'"
                                    >
                                        Contact
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Contact direct rapide -->
                    <div class="pt-4 flex items-center gap-3 text-xs font-medium text-white/80">
                        <span>Besoin d’un échange direct&nbsp;?</span>
                        <a href="{{ $telUrl }}" class="hover:text-white underline underline-offset-4">Appeler</a>
                        <span>·</span>
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="hover:text-white underline underline-offset-4">WhatsApp</a>
                    </div>
                </div>

                <!-- Volet droit (7 colonnes) : Carte blanche avec formulaire multi-étapes -->
                <div class="lg:col-span-7 rounded-[24px] sm:rounded-[32px] bg-white text-ink p-6 sm:p-8 md:p-10 shadow-2xl flex flex-col justify-between min-h-[480px]">
                    
                    <!-- Confirmation après envoi -->
                    <div x-show="submitted" x-cloak class="text-center py-10 space-y-6 my-auto">
                        <div class="size-16 rounded-full bg-primary/10 text-primary flex items-center justify-center mx-auto text-2xl font-bold">
                            ✓
                        </div>
                        <div class="space-y-2">
                            <h3 class="font-heading font-extrabold text-2xl sm:text-[28px] text-ink">
                                Merci, votre demande est bien arrivée.
                            </h3>
                            <p class="font-body text-base text-muted max-w-md mx-auto leading-relaxed">
                                Je lis vos réponses avec attention et je reviens vers vous avec une proposition. Vous pouvez aussi m’écrire dès maintenant.
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                            <x-ui.button variant="ghost" :href="$telUrl">
                                <svg viewBox="0 0 24 24" class="size-5 inline-block mr-1.5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                                </svg>
                                Appeler
                            </x-ui.button>
                            <x-ui.button variant="ghost" :href="$whatsappUrl" target="_blank" rel="noopener">
                                <x-ui.icon-whatsapp class="size-5 inline-block mr-1.5" />
                                WhatsApp
                            </x-ui.button>
                        </div>
                    </div>

                    <!-- Message d'erreur d'envoi -->
                    <div x-show="submitError" x-cloak class="p-4 rounded-2xl bg-surface border border-ink text-ink font-body text-sm mb-4 flex items-start gap-2" role="alert"><svg viewBox="0 0 20 20" class="size-4 shrink-0 text-primary" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.5a.75.75 0 00-1.5 0v4a.75.75 0 001.5 0v-4zM10 14.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                        Votre demande n’a pas pu être envoyée. Vérifiez votre connexion et réessayez, ou écrivez-moi directement sur WhatsApp.
                    </div>

                    <!-- Formulaire interactif 5 étapes -->
                    <form x-show="!submitted" @submit.prevent="submitForm" class="space-y-6">
                        {{-- Champ honeypot anti-spam masqué hors-écran --}}
                        <input type="text" name="website" x-model="honeypot" tabindex="-1" autocomplete="off" aria-hidden="true" class="absolute -left-[9999px]" />

                        {{-- ================= ÉTAPE 1 SUR 5 ================= --}}
                        <div x-show="step === 1" x-cloak class="space-y-5">
                            <div class="flex items-center justify-between border-b border-line pb-3">
                                <h3 class="font-heading font-bold text-lg sm:text-xl text-ink">
                                    Étape 1 sur 5 : Votre activité
                                </h3>
                                <span class="font-body text-xs font-semibold text-primary">1/5</span>
                            </div>

                            <!-- Champ 1 : Quelle est votre activité ? (obligatoire) -->
                            <div class="space-y-1.5">
                                <label for="f_activity" class="block font-heading font-semibold text-sm sm:text-base text-ink">
                                    Quelle est votre activité ? <span class="text-primary">*</span>
                                </label>
                                <p class="font-body text-xs text-muted">
                                    Que vendez-vous ou proposez-vous, et à qui ?
                                </p>
                                <textarea 
                                    id="f_activity" 
                                    x-model="formData.activity" 
                                    rows="3" 
                                    placeholder="Je vends des produits cosmétiques à des clientes qui commandent sur WhatsApp."
                                    class="w-full rounded-2xl border border-line bg-surface focus:bg-white focus:border-primary focus:outline-none p-3.5 font-body text-sm text-ink transition-colors"
                                    :class="{ 'border-ink': errors.activity }"
                                ></textarea>
                                <p x-show="errors.activity" class="flex items-center gap-1.5 font-body text-xs text-ink font-medium"><svg viewBox="0 0 20 20" class="size-4 shrink-0 text-primary" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.5a.75.75 0 00-1.5 0v4a.75.75 0 001.5 0v-4zM10 14.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg><span x-text="errors.activity"></span></p>
                            </div>

                            <!-- Champ 2 : Avez-vous déjà un site internet ? (obligatoire, choix unique) -->
                            <div class="space-y-2 pt-1">
                                <label class="block font-heading font-semibold text-sm sm:text-base text-ink">
                                    Avez-vous déjà un site internet ? <span class="text-primary">*</span>
                                </label>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-3 p-3 rounded-2xl border border-line cursor-pointer hover:bg-surface transition-colors" :class="{ 'border-primary bg-primary/5': formData.has_website === 'Non, pas encore' }">
                                        <input type="radio" value="Non, pas encore" x-model="formData.has_website" class="size-4 accent-primary" />
                                        <span class="font-body text-sm text-ink">Non, pas encore</span>
                                    </label>
                                    <label class="flex items-center gap-3 p-3 rounded-2xl border border-line cursor-pointer hover:bg-surface transition-colors" :class="{ 'border-primary bg-primary/5': formData.has_website === 'Seulement des réseaux sociaux ou WhatsApp' }">
                                        <input type="radio" value="Seulement des réseaux sociaux ou WhatsApp" x-model="formData.has_website" class="size-4 accent-primary" />
                                        <span class="font-body text-sm text-ink">Seulement des réseaux sociaux ou WhatsApp</span>
                                    </label>
                                    <label class="flex items-center gap-3 p-3 rounded-2xl border border-line cursor-pointer hover:bg-surface transition-colors" :class="{ 'border-primary bg-primary/5': formData.has_website === 'Oui, mais je veux le refaire' }">
                                        <input type="radio" value="Oui, mais je veux le refaire" x-model="formData.has_website" class="size-4 accent-primary" />
                                        <span class="font-body text-sm text-ink">Oui, mais je veux le refaire</span>
                                    </label>
                                </div>
                                <p x-show="errors.has_website" class="flex items-center gap-1.5 font-body text-xs text-ink font-medium"><svg viewBox="0 0 20 20" class="size-4 shrink-0 text-primary" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.5a.75.75 0 00-1.5 0v4a.75.75 0 001.5 0v-4zM10 14.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg><span x-text="errors.has_website"></span></p>
                            </div>
                        </div>

                        {{-- ================= ÉTAPE 2 SUR 5 ================= --}}
                        <div x-show="step === 2" x-cloak class="space-y-5">
                            <div class="flex items-center justify-between border-b border-line pb-3">
                                <h3 class="font-heading font-bold text-lg sm:text-xl text-ink">
                                    Étape 2 sur 5 : Les difficultés actuelles
                                </h3>
                                <span class="font-body text-xs font-semibold text-primary">2/5</span>
                            </div>

                            <!-- Champ 3 : Quelles sont vos difficultés actuelles ? (obligatoire, choix multiple) -->
                            <div class="space-y-2">
                                <label class="block font-heading font-semibold text-sm sm:text-base text-ink">
                                    Quelles sont vos difficultés actuelles ? <span class="text-primary">*</span>
                                </label>
                                <p class="font-body text-xs text-muted">
                                    Cochez tout ce qui vous correspond.
                                </p>
                                <div class="space-y-2">
                                    <label class="flex items-start gap-3 p-3 rounded-2xl border border-line cursor-pointer hover:bg-surface transition-colors">
                                        <input type="checkbox" value="Je réponds sans cesse aux mêmes questions sur WhatsApp" x-model="formData.difficulties" class="size-4 mt-0.5 accent-primary rounded" />
                                        <span class="font-body text-sm text-ink leading-snug">Je réponds sans cesse aux mêmes questions sur WhatsApp</span>
                                    </label>
                                    <label class="flex items-start gap-3 p-3 rounded-2xl border border-line cursor-pointer hover:bg-surface transition-colors">
                                        <input type="checkbox" value="Je présente mes produits ou mes services un par un" x-model="formData.difficulties" class="size-4 mt-0.5 accent-primary rounded" />
                                        <span class="font-body text-sm text-ink leading-snug">Je présente mes produits ou mes services un par un</span>
                                    </label>
                                    <label class="flex items-start gap-3 p-3 rounded-2xl border border-line cursor-pointer hover:bg-surface transition-colors">
                                        <input type="checkbox" value="Je prends mes commandes à la main" x-model="formData.difficulties" class="size-4 mt-0.5 accent-primary rounded" />
                                        <span class="font-body text-sm text-ink leading-snug">Je prends mes commandes à la main</span>
                                    </label>
                                    <label class="flex items-start gap-3 p-3 rounded-2xl border border-line cursor-pointer hover:bg-surface transition-colors">
                                        <input type="checkbox" value="Je n’ai aucune page qui explique clairement mon offre" x-model="formData.difficulties" class="size-4 mt-0.5 accent-primary rounded" />
                                        <span class="font-body text-sm text-ink leading-snug">Je n’ai aucune page qui explique clairement mon offre</span>
                                    </label>
                                    <label class="flex items-start gap-3 p-3 rounded-2xl border border-line cursor-pointer hover:bg-surface transition-colors">
                                        <input type="checkbox" value="Mes clients viennent uniquement par le bouche-à-oreille" x-model="formData.difficulties" class="size-4 mt-0.5 accent-primary rounded" />
                                        <span class="font-body text-sm text-ink leading-snug">Mes clients viennent uniquement par le bouche-à-oreille</span>
                                    </label>
                                    <label class="flex items-start gap-3 p-3 rounded-2xl border border-line cursor-pointer hover:bg-surface transition-colors">
                                        <input type="checkbox" value="Autre difficulté" x-model="formData.difficulties" class="size-4 mt-0.5 accent-primary rounded" />
                                        <span class="font-body text-sm text-ink leading-snug">Autre difficulté</span>
                                    </label>
                                </div>
                                <p x-show="errors.difficulties" class="flex items-center gap-1.5 font-body text-xs text-ink font-medium"><svg viewBox="0 0 20 20" class="size-4 shrink-0 text-primary" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.5a.75.75 0 00-1.5 0v4a.75.75 0 001.5 0v-4zM10 14.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg><span x-text="errors.difficulties"></span></p>
                            </div>

                            <!-- Champ 4 : Précisez votre difficulté principale (facultatif) -->
                            <div class="space-y-1.5 pt-1">
                                <label for="f_main_diff" class="block font-heading font-semibold text-sm sm:text-base text-ink">
                                    Précisez votre difficulté principale <span class="text-muted font-normal text-xs">(facultatif)</span>
                                </label>
                                <textarea 
                                    id="f_main_diff" 
                                    x-model="formData.main_difficulty" 
                                    rows="2" 
                                    placeholder="Je perds environ une heure par jour à répondre aux mêmes questions."
                                    class="w-full rounded-2xl border border-line bg-surface focus:bg-white focus:border-primary focus:outline-none p-3.5 font-body text-sm text-ink transition-colors"
                                ></textarea>
                            </div>
                        </div>

                        {{-- ================= ÉTAPE 3 SUR 5 ================= --}}
                        <div x-show="step === 3" x-cloak class="space-y-5">
                            <div class="flex items-center justify-between border-b border-line pb-3">
                                <h3 class="font-heading font-bold text-lg sm:text-xl text-ink">
                                    Étape 3 sur 5 : Votre objectif et votre besoin
                                </h3>
                                <span class="font-body text-xs font-semibold text-primary">3/5</span>
                            </div>

                            <!-- Champ 5 : Quel est l’objectif de votre site ? (obligatoire) -->
                            <div class="space-y-1.5">
                                <label for="f_objective" class="block font-heading font-semibold text-sm sm:text-base text-ink">
                                    Quel est l’objectif de votre site ? <span class="text-primary">*</span>
                                </label>
                                <p class="font-body text-xs text-muted">
                                    Dites-moi ce que votre site doit accomplir pour votre activité.
                                </p>
                                <textarea 
                                    id="f_objective" 
                                    x-model="formData.objective" 
                                    rows="2" 
                                    placeholder="Recevoir des demandes de clients sans répondre aux mêmes questions."
                                    class="w-full rounded-2xl border border-line bg-surface focus:bg-white focus:border-primary focus:outline-none p-3.5 font-body text-sm text-ink transition-colors"
                                    :class="{ 'border-ink': errors.objective }"
                                ></textarea>
                                <p x-show="errors.objective" class="flex items-center gap-1.5 font-body text-xs text-ink font-medium"><svg viewBox="0 0 20 20" class="size-4 shrink-0 text-primary" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.5a.75.75 0 00-1.5 0v4a.75.75 0 001.5 0v-4zM10 14.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg><span x-text="errors.objective"></span></p>
                            </div>

                            <!-- Champ 6 : Décrivez votre besoin (obligatoire) -->
                            <div class="space-y-1.5 pt-1">
                                <label for="f_need" class="block font-heading font-semibold text-sm sm:text-base text-ink">
                                    Décrivez votre besoin <span class="text-primary">*</span>
                                </label>
                                <p class="font-body text-xs text-muted">
                                    Parlez-moi de vos attentes, de vos clients cibles et des fonctionnalités clés.
                                </p>
                                <textarea 
                                    id="f_need" 
                                    x-model="formData.need" 
                                    rows="3" 
                                    placeholder="Mes clients sont des femmes de 25 à 45 ans. Je voudrais une page de présentation de mes produits et un bouton WhatsApp."
                                    class="w-full rounded-2xl border border-line bg-surface focus:bg-white focus:border-primary focus:outline-none p-3.5 font-body text-sm text-ink transition-colors"
                                    :class="{ 'border-ink': errors.need }"
                                ></textarea>
                                <p x-show="errors.need" class="flex items-center gap-1.5 font-body text-xs text-ink font-medium"><svg viewBox="0 0 20 20" class="size-4 shrink-0 text-primary" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.5a.75.75 0 00-1.5 0v4a.75.75 0 001.5 0v-4zM10 14.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg><span x-text="errors.need"></span></p>
                            </div>
                        </div>

                        {{-- ================= ÉTAPE 4 SUR 5 ================= --}}
                        <div x-show="step === 4" x-cloak class="space-y-5">
                            <div class="flex items-center justify-between border-b border-line pb-3">
                                <h3 class="font-heading font-bold text-lg sm:text-xl text-ink">
                                    Étape 4 sur 5 : Votre budget
                                </h3>
                                <span class="font-body text-xs font-semibold text-primary">4/5</span>
                            </div>

                            <!-- Champ 7 : Quel budget avez-vous prévu pour avoir un site ? (obligatoire) -->
                            <div class="space-y-3">
                                <div>
                                    <label class="block font-heading font-semibold text-sm sm:text-base text-ink">
                                        Quel budget avez-vous prévu pour avoir un site ? <span class="text-primary">*</span>
                                    </label>
                                    <p class="font-body text-xs text-muted mt-1">
                                        Choisissez directement la tranche qui correspond à votre projet ou indiquez un montant libre.
                                    </p>
                                </div>

                                <!-- Grille des 6 options de budget -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                                    
                                    <!-- Option 1 : Moins de 100 000 FCFA -->
                                    <button 
                                        type="button"
                                        @click="selectBudget('Moins de 100 000 FCFA')"
                                        class="p-4 rounded-2xl border text-left transition-all flex items-center justify-between group cursor-pointer"
                                        :class="budgetOption === 'Moins de 100 000 FCFA' && !formData.budget_unknown 
                                            ? 'border-primary bg-primary/5 ring-1 ring-primary shadow-sm' 
                                            : 'border-line bg-white hover:border-ink/20 hover:bg-surface'"
                                    >
                                        <span class="font-body text-sm font-semibold text-ink group-hover:text-primary transition-colors">
                                            Moins de 100 000 FCFA
                                        </span>
                                        <div class="size-5 rounded-full border flex items-center justify-center transition-all shrink-0 ml-2"
                                             :class="budgetOption === 'Moins de 100 000 FCFA' && !formData.budget_unknown ? 'border-primary bg-primary text-white' : 'border-line bg-surface'">
                                            <svg x-show="budgetOption === 'Moins de 100 000 FCFA' && !formData.budget_unknown" class="size-3 stroke-[3]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                        </div>
                                    </button>

                                    <!-- Option 2 : 100k – 300k FCFA -->
                                    <button 
                                        type="button"
                                        @click="selectBudget('100k – 300k FCFA')"
                                        class="p-4 rounded-2xl border text-left transition-all flex items-center justify-between group cursor-pointer"
                                        :class="budgetOption === '100k – 300k FCFA' && !formData.budget_unknown 
                                            ? 'border-primary bg-primary/5 ring-1 ring-primary shadow-sm' 
                                            : 'border-line bg-white hover:border-ink/20 hover:bg-surface'"
                                    >
                                        <span class="font-body text-sm font-semibold text-ink group-hover:text-primary transition-colors">
                                            100k – 300k FCFA
                                        </span>
                                        <div class="size-5 rounded-full border flex items-center justify-center transition-all shrink-0 ml-2"
                                             :class="budgetOption === '100k – 300k FCFA' && !formData.budget_unknown ? 'border-primary bg-primary text-white' : 'border-line bg-surface'">
                                            <svg x-show="budgetOption === '100k – 300k FCFA' && !formData.budget_unknown" class="size-3 stroke-[3]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                        </div>
                                    </button>

                                    <!-- Option 3 : 300k – 500k FCFA -->
                                    <button 
                                        type="button"
                                        @click="selectBudget('300k – 500k FCFA')"
                                        class="p-4 rounded-2xl border text-left transition-all flex items-center justify-between group cursor-pointer"
                                        :class="budgetOption === '300k – 500k FCFA' && !formData.budget_unknown 
                                            ? 'border-primary bg-primary/5 ring-1 ring-primary shadow-sm' 
                                            : 'border-line bg-white hover:border-ink/20 hover:bg-surface'"
                                    >
                                        <span class="font-body text-sm font-semibold text-ink group-hover:text-primary transition-colors">
                                            300k – 500k FCFA
                                        </span>
                                        <div class="size-5 rounded-full border flex items-center justify-center transition-all shrink-0 ml-2"
                                             :class="budgetOption === '300k – 500k FCFA' && !formData.budget_unknown ? 'border-primary bg-primary text-white' : 'border-line bg-surface'">
                                            <svg x-show="budgetOption === '300k – 500k FCFA' && !formData.budget_unknown" class="size-3 stroke-[3]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                        </div>
                                    </button>

                                    <!-- Option 4 : 500k – 1M FCFA -->
                                    <button 
                                        type="button"
                                        @click="selectBudget('500k – 1M FCFA')"
                                        class="p-4 rounded-2xl border text-left transition-all flex items-center justify-between group cursor-pointer"
                                        :class="budgetOption === '500k – 1M FCFA' && !formData.budget_unknown 
                                            ? 'border-primary bg-primary/5 ring-1 ring-primary shadow-sm' 
                                            : 'border-line bg-white hover:border-ink/20 hover:bg-surface'"
                                    >
                                        <span class="font-body text-sm font-semibold text-ink group-hover:text-primary transition-colors">
                                            500k – 1M FCFA
                                        </span>
                                        <div class="size-5 rounded-full border flex items-center justify-center transition-all shrink-0 ml-2"
                                             :class="budgetOption === '500k – 1M FCFA' && !formData.budget_unknown ? 'border-primary bg-primary text-white' : 'border-line bg-surface'">
                                            <svg x-show="budgetOption === '500k – 1M FCFA' && !formData.budget_unknown" class="size-3 stroke-[3]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                        </div>
                                    </button>

                                    <!-- Option 5 : Plus de 1M FCFA -->
                                    <button 
                                        type="button"
                                        @click="selectBudget('Plus de 1M FCFA')"
                                        class="p-4 rounded-2xl border text-left transition-all flex items-center justify-between group cursor-pointer"
                                        :class="budgetOption === 'Plus de 1M FCFA' && !formData.budget_unknown 
                                            ? 'border-primary bg-primary/5 ring-1 ring-primary shadow-sm' 
                                            : 'border-line bg-white hover:border-ink/20 hover:bg-surface'"
                                    >
                                        <span class="font-body text-sm font-semibold text-ink group-hover:text-primary transition-colors">
                                            Plus de 1M FCFA
                                        </span>
                                        <div class="size-5 rounded-full border flex items-center justify-center transition-all shrink-0 ml-2"
                                             :class="budgetOption === 'Plus de 1M FCFA' && !formData.budget_unknown ? 'border-primary bg-primary text-white' : 'border-line bg-surface'">
                                            <svg x-show="budgetOption === 'Plus de 1M FCFA' && !formData.budget_unknown" class="size-3 stroke-[3]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                        </div>
                                    </button>

                                    <!-- Option 6 : Montant libre -->
                                    <button 
                                        type="button"
                                        @click="selectBudget('custom')"
                                        class="p-4 rounded-2xl border text-left transition-all flex items-center justify-between group cursor-pointer"
                                        :class="budgetOption === 'custom' && !formData.budget_unknown 
                                            ? 'border-primary bg-primary/5 ring-1 ring-primary shadow-sm' 
                                            : 'border-line bg-white hover:border-ink/20 hover:bg-surface'"
                                    >
                                        <div>
                                            <span class="font-body text-sm font-semibold text-ink group-hover:text-primary transition-colors block">
                                                Montant libre
                                            </span>
                                            <span class="text-[11px] text-muted">Saisir un autre montant</span>
                                        </div>
                                        <div class="size-5 rounded-full border flex items-center justify-center transition-all shrink-0 ml-2"
                                             :class="budgetOption === 'custom' && !formData.budget_unknown ? 'border-primary bg-primary text-white' : 'border-line bg-surface'">
                                            <svg x-show="budgetOption === 'custom' && !formData.budget_unknown" class="size-3 stroke-[3]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                        </div>
                                    </button>

                                </div>

                                <!-- Champ de saisie pour montant libre -->
                                <div x-show="budgetOption === 'custom' && !formData.budget_unknown" x-transition class="space-y-1.5 pt-2">
                                    <label for="f_custom_budget" class="block font-heading font-medium text-xs text-ink">
                                        Indiquez votre montant ou votre fourchette <span class="text-primary">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="f_custom_budget" 
                                        x-model="customBudget" 
                                        @input="updateCustomBudget(customBudget)"
                                        placeholder="Ex : 250 000 FCFA, 800 000 FCFA, 1 500 €..."
                                        class="w-full rounded-2xl border border-line bg-surface focus:bg-white focus:border-primary focus:outline-none p-3.5 font-body text-sm text-ink transition-colors"
                                        :class="{ 'border-ink': errors.budget && budgetOption === 'custom' }"
                                    />
                                </div>

                                <!-- Option 'Je ne sais pas encore, conseillez-moi' -->
                                <label class="flex items-center gap-3 p-3.5 rounded-2xl border transition-all cursor-pointer mt-3" 
                                       :class="formData.budget_unknown ? 'border-primary bg-primary/5 ring-1 ring-primary shadow-sm' : 'border-line bg-white hover:bg-surface'">
                                    <input 
                                        type="checkbox" 
                                        x-model="formData.budget_unknown" 
                                        @change="toggleBudgetUnknown()"
                                        class="size-4 accent-primary rounded" 
                                    />
                                    <span class="font-body text-sm text-ink font-medium">Je ne sais pas encore, conseillez-moi</span>
                                </label>

                                <p x-show="errors.budget" class="flex items-center gap-1.5 font-body text-xs text-ink font-medium pt-1"><svg viewBox="0 0 20 20" class="size-4 shrink-0 text-primary" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.5a.75.75 0 00-1.5 0v4a.75.75 0 001.5 0v-4zM10 14.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg><span x-text="errors.budget"></span></p>
                            </div>
                        </div>

                        {{-- ================= ÉTAPE 5 SUR 5 ================= --}}
                        <div x-show="step === 5" x-cloak class="space-y-5">
                            <div class="flex items-center justify-between border-b border-line pb-3">
                                <h3 class="font-heading font-bold text-lg sm:text-xl text-ink">
                                    Étape 5 sur 5 : Vos coordonnées
                                </h3>
                                <span class="font-body text-xs font-semibold text-primary">5/5</span>
                            </div>

                            <p class="font-body text-sm text-muted">
                                Comment puis-je vous recontacter pour vous envoyer une proposition ?
                            </p>

                            <!-- Champ 8 : Nom complet (obligatoire) -->
                            <div class="space-y-1">
                                <label for="f_name" class="block font-heading font-semibold text-sm text-ink">
                                    Nom complet <span class="text-primary">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="f_name" 
                                    x-model="formData.full_name" 
                                    placeholder="Votre nom complet"
                                    class="w-full rounded-2xl border border-line bg-surface focus:bg-white focus:border-primary focus:outline-none p-3 font-body text-sm text-ink transition-colors"
                                    :class="{ 'border-ink': errors.full_name }"
                                />
                                <p x-show="errors.full_name" class="flex items-center gap-1.5 font-body text-xs text-ink font-medium"><svg viewBox="0 0 20 20" class="size-4 shrink-0 text-primary" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.5a.75.75 0 00-1.5 0v4a.75.75 0 001.5 0v-4zM10 14.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg><span x-text="errors.full_name"></span></p>
                            </div>

                            <!-- Champ 9 : Email professionnel (obligatoire) -->
                            <div class="space-y-1">
                                <label for="f_email" class="block font-heading font-semibold text-sm text-ink">
                                    Email professionnel <span class="text-primary">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    id="f_email" 
                                    x-model="formData.email" 
                                    placeholder="nom@entreprise.com"
                                    class="w-full rounded-2xl border border-line bg-surface focus:bg-white focus:border-primary focus:outline-none p-3 font-body text-sm text-ink transition-colors"
                                    :class="{ 'border-ink': errors.email }"
                                />
                                <p x-show="errors.email" class="flex items-center gap-1.5 font-body text-xs text-ink font-medium"><svg viewBox="0 0 20 20" class="size-4 shrink-0 text-primary" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.5a.75.75 0 00-1.5 0v4a.75.75 0 001.5 0v-4zM10 14.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg><span x-text="errors.email"></span></p>
                            </div>

                            <!-- Champ 10 : Téléphone / WhatsApp (obligatoire) -->
                            <div class="space-y-1">
                                <label for="f_phone" class="block font-heading font-semibold text-sm text-ink">
                                    Téléphone / WhatsApp <span class="text-primary">*</span>
                                </label>
                                <p class="font-body text-xs text-muted">
                                    Votre numéro avec l’indicatif du pays.
                                </p>
                                <input 
                                    type="tel" 
                                    id="f_phone" 
                                    x-model="formData.phone" 
                                    placeholder="+229 97 00 00 00"
                                    class="w-full rounded-2xl border border-line bg-surface focus:bg-white focus:border-primary focus:outline-none p-3 font-body text-sm text-ink transition-colors"
                                    :class="{ 'border-ink': errors.phone }"
                                />
                                <p x-show="errors.phone" class="flex items-center gap-1.5 font-body text-xs text-ink font-medium"><svg viewBox="0 0 20 20" class="size-4 shrink-0 text-primary" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.5a.75.75 0 00-1.5 0v4a.75.75 0 001.5 0v-4zM10 14.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg><span x-text="errors.phone"></span></p>
                            </div>

                            <!-- Case de consentement (obligatoire) -->
                            <div class="space-y-1 pt-1">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        x-model="formData.consent" 
                                        class="size-4 mt-0.5 accent-primary rounded" 
                                    />
                                    <span class="font-body text-xs text-muted leading-snug">
                                        J’accepte que mes informations soient utilisées pour répondre à ma demande. Voir la <a href="{{ route('politique-confidentialite') }}" target="_blank" rel="noopener" class="font-medium text-ink underline decoration-primary underline-offset-2 hover:text-primary">politique de confidentialité</a>.
                                    </span>
                                </label>
                                <p x-show="errors.consent" class="flex items-center gap-1.5 font-body text-xs text-ink font-medium"><svg viewBox="0 0 20 20" class="size-4 shrink-0 text-primary" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.5a.75.75 0 00-1.5 0v4a.75.75 0 001.5 0v-4zM10 14.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg><span x-text="errors.consent"></span></p>
                            </div>
                        </div>

                        <!-- Boutons de navigation officiels -->
                        <div class="pt-6 border-t border-line flex items-center justify-between gap-3">
                            <div>
                                <button 
                                    type="button" 
                                    x-show="step > 1" 
                                    @click="prevStep()" 
                                    class="h-11 px-6 rounded-full border border-line bg-white hover:bg-surface text-ink font-body font-semibold text-sm transition-colors"
                                >
                                    Retour
                                </button>
                            </div>

                            <div>
                                <!-- Bouton Continuer (Étapes 1 à 4) -->
                                <button 
                                    type="button" 
                                    x-show="step < 5" 
                                    @click="nextStep()" 
                                    class="h-11 px-8 rounded-full bg-primary hover:bg-primary-hover text-white font-body font-bold text-sm shadow-sm transition-all"
                                >
                                    Continuer
                                </button>

                                <!-- Bouton final (Étape 5) -->
                                <button 
                                    type="submit" 
                                    x-show="step === 5" 
                                    :disabled="sending"
                                    class="h-11 px-8 rounded-full bg-primary hover:bg-primary-hover text-white font-body font-bold text-sm shadow-sm transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    Envoyer ma demande
                                </button>
                            </div>
                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>
</x-layout.section>
</x-layouts::app>
