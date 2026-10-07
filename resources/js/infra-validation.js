/**
 * AquaSecure - Système de validation des formulaires (Infrastructures & Maintenances)
 * Remplace la validation native HTML5 par un système interactif, clair et élégant.
 */

export function initInfraValidation() {
    const forms = document.querySelectorAll('.validated-form');
    if (!forms.length) return;

    forms.forEach((form) => {
        // Désactive les bulles natives HTML5 du navigateur
        form.noValidate = true;

        const inputs = form.querySelectorAll('input:not([type="hidden"]):not([type="submit"]), select, textarea');

        // Conteneur de résumé des erreurs en haut du formulaire
        let summaryBanner = form.querySelector('.form-error-summary-banner');
        if (!summaryBanner) {
            summaryBanner = document.createElement('div');
            summaryBanner.className = 'form-error-summary-banner';
            summaryBanner.style.display = 'none';
            form.prepend(summaryBanner);
        }

        // Valider un champ individuel
        function validateField(input, isSubmitting = false) {
            const rules = (input.dataset.rules || '').split('|').filter(Boolean);
            const label = input.dataset.label || input.name || 'Ce champ';
            const val = input.value !== undefined ? input.value.trim() : '';
            let error = null;

            // 1. Règle "required"
            if (rules.includes('required') || input.hasAttribute('required')) {
                if (val === '') {
                    error = `Le champ « ${label} » est obligatoire.`;
                }
            }

            // Si le champ n'est pas vide, valider les règles de format
            if (!error && val !== '') {
                for (const rule of rules) {
                    // Contient uniquement des lettres (accents, espaces, tirets, apostrophes)
                    if (rule === 'letters_only') {
                        const lettersRegex = /^[\p{L}\s\-'.]+$/u;
                        if (!lettersRegex.test(val)) {
                            error = `Le champ « ${label} » ne doit contenir que des lettres, espaces ou tirets (aucun chiffre ni symbole).`;
                            break;
                        }
                    }

                    // Format texte avec lettres obligatoires (lettres, chiffres, espaces, tirets, etc.)
                    if (rule === 'letters_format') {
                        const hasLetter = /[\p{L}]/u.test(val);
                        const validChars = /^[\p{L}0-9\s\-'.(),]+$/u.test(val);
                        if (!hasLetter || !validChars) {
                            error = `Le champ « ${label} » doit contenir des lettres (seuls les lettres, chiffres, espaces et tirets sont acceptés).`;
                            break;
                        }
                    }

                    // Code référence (ex: INF-2026-001)
                    if (rule === 'reference_code') {
                        const refRegex = /^[A-Za-z0-9\-_]+$/;
                        if (!refRegex.test(val)) {
                            error = `Le code référence doit respecter le format standard (lettres, chiffres, tirets - ex: INF-2026-001).`;
                            break;
                        }
                    }

                    // Longueur minimale
                    if (rule.startsWith('min:')) {
                        const min = parseInt(rule.split(':')[1], 10);
                        if (val.length < min) {
                            error = `Le champ « ${label} » doit comporter au moins ${min} caractères.`;
                            break;
                        }
                    }

                    // Longueur maximale
                    if (rule.startsWith('max:')) {
                        const max = parseInt(rule.split(':')[1], 10);
                        if (val.length > max) {
                            error = `Le champ « ${label} » ne peut pas dépasser ${max} caractères.`;
                            break;
                        }
                    }

                    // Nombre positif ou nul
                    if (rule === 'positive_number') {
                        const num = parseFloat(val);
                        if (isNaN(num) || num < 0) {
                            error = `Le champ « ${label} » doit être un nombre supérieur ou égal à 0.`;
                            break;
                        }
                    }

                    // Nombre compris entre min et max
                    if (rule.startsWith('number_between:')) {
                        const parts = rule.split(':');
                        const min = parseFloat(parts[1]);
                        const max = parseFloat(parts[2]);
                        const num = parseFloat(val);
                        if (isNaN(num) || num < min || num > max) {
                            error = `Le champ « ${label} » doit être une valeur comprise entre ${min} et ${max}.`;
                            break;
                        }
                    }

                    // Téléphone
                    if (rule === 'phone') {
                        const phoneRegex = /^[0-9\+\-\s\(\)]{8,25}$/;
                        if (!phoneRegex.test(val)) {
                            error = `Veuillez saisir un numéro de téléphone valide (ex: +216 71 888 101).`;
                            break;
                        }
                    }
                }
            }

            // Règles métier contextuelles (Maintenance & Infrastructure)
            if (!error) {
                // Maintenance : résultat obligatoire si terminée
                if (input.name === 'result') {
                    const statusSelect = form.querySelector('[name="status"]');
                    if (statusSelect && statusSelect.value === 'completed' && val === '') {
                        error = `Le compte-rendu technique est obligatoire lorsqu’une maintenance est marquée comme « Terminée ».`;
                    }
                }

                // Maintenance : cohérence dates début & fin
                if (input.name === 'completed_at') {
                    const startedInput = form.querySelector('[name="started_at"]');
                    if (startedInput && startedInput.value && val) {
                        if (new Date(val) < new Date(startedInput.value)) {
                            error = `La date d'achèvement doit être égale ou postérieure à la date de début sur site.`;
                        }
                    }
                }

                // Maintenance : date planifiée dans le futur si planned
                if (input.name === 'scheduled_at') {
                    const statusSelect = form.querySelector('[name="status"]');
                    if (statusSelect && statusSelect.value === 'planned' && val) {
                        const schedDate = new Date(val);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        if (schedDate < today) {
                            error = `Pour une maintenance « Planifiée », la date prévisionnelle doit être aujourd'hui ou dans le futur.`;
                        }
                    }
                }

                // Infrastructure : mise en service >= installation
                if (input.name === 'commissioning_date') {
                    const installInput = form.querySelector('[name="installation_date"]');
                    if (installInput && installInput.value && val) {
                        if (new Date(val) < new Date(installInput.value)) {
                            error = `La date de mise en service doit être égale ou postérieure à la date d'installation.`;
                        }
                    }
                }

                // Infrastructure : prochaine maintenance > dernière maintenance
                if (input.name === 'next_maintenance_date') {
                    const lastInput = form.querySelector('[name="last_maintenance_date"]');
                    if (lastInput && lastInput.value && val) {
                        if (new Date(val) <= new Date(lastInput.value)) {
                            error = `La date de prochaine maintenance doit être strictement postérieure à la dernière maintenance.`;
                        }
                    }
                }
            }

            // Affichage du retour visuel
            renderFeedback(input, error, isSubmitting);
            return error;
        }

        // Rendu du feedback d'erreur / succès sur le champ
        function renderFeedback(input, errorMsg, isSubmitting) {
            // Trouver le conteneur de feedback
            let feedbackContainer = input.parentElement.querySelector(`.validation-feedback[data-field="${input.name}"]`);
            if (!feedbackContainer) {
                feedbackContainer = input.parentElement.querySelector('.validation-feedback');
            }
            if (!feedbackContainer) {
                feedbackContainer = document.createElement('div');
                feedbackContainer.className = 'validation-feedback';
                feedbackContainer.dataset.field = input.name;
                input.insertAdjacentElement('afterend', feedbackContainer);
            }

            // Supprimer les anciens messages serveur statiques quand l'utilisateur corrige
            const existingServerErrors = input.parentElement.querySelectorAll('.field-error:not(.client-feedback)');
            existingServerErrors.forEach(el => el.remove());

            if (errorMsg) {
                input.classList.add('is-invalid');
                input.classList.remove('is-valid');
                feedbackContainer.innerHTML = `
                    <span class="field-error-badge client-feedback" role="alert">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>${errorMsg}</span>
                    </span>
                `;
            } else {
                input.classList.remove('is-invalid');
                if (input.value.trim() !== '') {
                    input.classList.add('is-valid');
                } else {
                    input.classList.remove('is-valid');
                }
                feedbackContainer.innerHTML = '';
            }
        }

        // Écouter les interactions utilisateur
        inputs.forEach((input) => {
            // Sur la perte de focus (blur) : valider le champ
            input.addEventListener('blur', () => {
                input.dataset.touched = 'true';
                validateField(input);
            });

            // Sur la saisie (input / change) : revalider si déjà touché ou invalide
            const handleInput = () => {
                if (input.dataset.touched === 'true' || input.classList.contains('is-invalid')) {
                    validateField(input);
                }
            };
            input.addEventListener('input', handleInput);
            input.addEventListener('change', handleInput);
        });

        // Validation globale lors de la soumission du formulaire
        form.addEventListener('submit', (e) => {
            const errors = [];
            let firstInvalidInput = null;

            inputs.forEach((input) => {
                input.dataset.touched = 'true';
                const err = validateField(input, true);
                if (err) {
                    errors.push({
                        input,
                        label: input.dataset.label || input.name || 'Champ',
                        message: err
                    });
                    if (!firstInvalidInput) {
                        firstInvalidInput = input;
                    }
                }
            });

            if (errors.length > 0) {
                e.preventDefault();
                e.stopPropagation();

                // Afficher le bandeau récapitulatif élégant
                summaryBanner.style.display = 'block';
                summaryBanner.innerHTML = `
                    <div class="summary-banner-title">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>Veuillez corriger ${errors.length} erreur${errors.length > 1 ? 's' : ''} avant d'enregistrer :</span>
                    </div>
                    <ul class="summary-error-list">
                        ${errors.map((item, index) => `
                            <li>
                                <a href="javascript:void(0)" data-focus-target="${item.input.name}">
                                    ${item.label} : ${item.message}
                                </a>
                            </li>
                        `).join('')}
                    </ul>
                `;

                // Clics sur les liens du bandeau pour se déplacer vers le champ en erreur
                summaryBanner.querySelectorAll('[data-focus-target]').forEach((link) => {
                    link.addEventListener('click', () => {
                        const targetName = link.dataset.focusTarget;
                        const targetInput = form.querySelector(`[name="${targetName}"]`);
                        if (targetInput) {
                            targetInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            targetInput.focus();
                        }
                    });
                });

                // Défilement doux vers le bandeau d'erreur et focus sur le premier champ
                summaryBanner.scrollIntoView({ behavior: 'smooth', block: 'start' });
                if (firstInvalidInput) {
                    setTimeout(() => firstInvalidInput.focus(), 300);
                }
            } else {
                summaryBanner.style.display = 'none';
            }
        });
    });
}

// Auto-initialisation au chargement du DOM
if (typeof document !== 'undefined') {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initInfraValidation);
    } else {
        initInfraValidation();
    }
}
