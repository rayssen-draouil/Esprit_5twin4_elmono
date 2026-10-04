@extends('layouts.front')

@section('content')
    <section class="page-hero compact">
        <span class="eyebrow">CONTRIBUER À LA SÉCURITÉ</span>
        <h1>Signaler un<br><em>incident.</em></h1>
        <p>Votre signalement permet à nos équipes d'intervenir rapidement.</p>
    </section>
    <section class="section contact-layout">
        <form class="contact-form" enctype="multipart/form-data">
            <label>Type d'incident
                <select required>
                    <option value="">Sélectionner une catégorie</option>
                    <option>Fuite</option>
                    <option>Contamination</option>
                    <option>Coupure liée à la sécheresse</option>
                    <option>Équipement hors ligne</option>
                </select>
            </label>
            <label>Titre
                <input type="text" placeholder="Ex. Fuite sur conduite principale" required>
            </label>
            <label>Description
                <textarea rows="6" placeholder="Décrivez ce que vous avez observé..." required></textarea>
            </label>
            <label>Localisation
                <input type="text" placeholder="Adresse, commune ou infrastructure" required>
            </label>
            <label>Niveau de gravité
                <select required>
                    <option>Faible</option>
                    <option selected>Moyenne</option>
                    <option>Élevée</option>
                    <option>Critique</option>
                </select>
            </label>
            <label>Date de l'incident
                <input type="date" required>
            </label>
            <label>Photo (facultatif)
                <input type="file" accept="image/*">
            </label>
            <button class="button button-primary" type="button">Envoyer le signalement ↗</button>
        </form>
        <div class="contact-info">
            <span class="eyebrow">BESOIN D'AIDE ?</span>
            <p>Pour une urgence immédiate, contactez votre responsable de site ou les services compétents.</p>
        </div>
    </section>
@endsection
