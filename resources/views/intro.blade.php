@extends('layouts.app')

@section('title', 'Hear Me Out — Noter son crush, crush rating')

@push('styles')
<style>
    .intro-container { max-width: 900px; margin: 3rem auto; padding: 2rem; }
    .intro-hero { background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); color: white; padding: 3rem; border-radius: 12px; box-shadow: 0 8px 30px rgba(0,0,0,0.08); }
    .intro-hero h1 { margin: 0 0 1rem; font-size: 2.4rem; }
    .intro-cta { margin-top: 1.5rem; }
    .note-box { background: #fff8e1; border-left: 4px solid #ffca28; padding: 1rem; border-radius: 8px; }
</style>
@endpush

@section('content')
<div class="intro-container">
    <div class="intro-hero">
        <h1>Hear Me Out — noter son crush, crush rating</h1>
        <p style="font-size:1.05rem; opacity:0.95;">Participez à une expérience communautaire drôle, bienveillante et un peu piquante : on publie nos crush, on demande votre avis, et on note — humoristique ou sérieux, tout est permis, tant que ça reste respectueux.</p>
        <div class="intro-cta">
            <a href="{{ route('intro.continue') }}" class="btn" style="background: white; color: #4b4cff; padding: 0.75rem 1.25rem; border-radius: 10px; font-weight: 700; text-decoration: none;">Je découvre — commencer à noter</a>
        </div>
    </div>

    <article style="margin-top:2rem; line-height:1.7; color:#222;">
        <h2>Qu'est-ce que "Hear Me Out" ?</h2>
        <p>
            Ici, on partage nos crush — pas pour blesser ni exposer quelqu'un, mais pour s'amuser, rire, et recevoir un retour honnête : noter son crush, donner une vibe, partager une anecdote. Le concept mélange <strong>noter son crush</strong> et <strong>crush rating</strong>, deux façons de donner un retour qui peuvent être drôles, gentiment piquantes, ou sincères.
        </p>

        <h3>Pourquoi participer ?</h3>
        <p>
            Parce que parfois on veut juste savoir ce que pensent les autres : est-ce charmant, marrant, trop audacieux ? Hear Me Out est un espace où l'on peut recevoir ce feedback, garder son anonymat, et explorer des formats inspirés d'une ancienne trend TikTok de 2024 — <em>hear me out</em> — où on présente une idée (ou un crush) et on attend le verdict.
        </p>

        <h3>Fonctionnalités et communauté</h3>
        <p>
            Sur la plateforme, vous pourrez :
        </p>
        <ul>
            <li>Publier un crush anonyme ou partiellement masqué</li>
            <li>Noter et réagir : <strong>noter son crush</strong> avec un système de vote et de pourcentages</li>
            <li>Voir le <strong>crush rating</strong> global et lire les commentaires (si autorisés)</li>
        </ul>

        <h3>À venir — « À qui est ce crush ? »</h3>
        <p>
            Idée en développement : créer des « rooms » où un groupe de personnes entre son nom à tour de rôle et ajoute son crush. Ensuite, on lance le jeu : <strong>À qui est ce crush ?</strong> On doit deviner à qui appartient chaque confession. C'est parfait pour soirées entre amis ou sessions en ligne — un mélange de quiz, devinettes et révélations (tout le monde joue en confiance et anonymat si souhaité).
        </p>

        <h3>Règles importantes</h3>
        <p class="note-box">
            Nous voulons absolument éviter toute exposition ou publication de personnes réelles. <strong>Ne publiez pas d'informations identifiantes sur des personnes réelles.</strong> Si vous voulez qu'un post soit supprimé, envoyez-moi un message depuis la partie <strong>Mon Compte</strong> et je m'occuperai de le retirer — n'oubliez pas de me partager le lien du post pour que je le supprime rapidement.
        </p>

        <p style="color:#b00020; font-weight:600;">
            svp ne mettez pasq de porn sinon je vais me faire strike mercient
        </p>

        <h3>SEO &amp; mots-clés</h3>
        <p>
            Cette page est optimisée pour les recherches autour de <em>noter son crush</em>, <em>crush rating</em> et <em>hear me out</em>. Si vous cherchez à noter un crush ou à explorer la mécanique <strong>crush rating</strong>, vous êtes au bon endroit.
        </p>

        <h3>Respect et modération</h3>
        <p>
            Notre objectif est un espace amusant et respectueux. Les contenus visant à harceler, doxxer ou humilier quelqu'un ne sont pas acceptés. En participant, vous acceptez ces règles — et si un contenu doit être retiré, contactez-moi depuis <strong>Mon Compte</strong>.
        </p>

        <div style="margin-top:2rem; text-align:center;">
            <a href="{{ route('home') }}" class="btn" style="padding: 0.9rem 1.8rem; border-radius: 10px; background: #4b4cff; color: white; text-decoration: none; font-weight:700;">Parcourir les crushes</a>
        </div>
    </article>
</div>

@endsection
