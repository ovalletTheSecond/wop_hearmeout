@extends('layouts.app')

@section('title', 'Me Soutenir - Hear Me Out')

@section('content')
<div style="max-width: 600px; margin: 0 auto; text-align: center; padding: 2rem;">
    <h2 style="margin-bottom: 2rem;">Très Honorable Messire</h2>
    
    <p style="font-size: 1.2rem; line-height: 1.6; margin-bottom: 2rem;">
        Votre présence en ces lieux m'honore grandement. Si vous souhaitez témoigner votre soutien à cette modeste entreprise, 
        votre généreuse contribution, aussi minime soit-elle, sera accueillie avec la plus profonde gratitude.
    </p>

    <div id="donationSection">
        <a href="https://paypal.me/YourPayPalLink" class="btn btn-primary" style="font-size: 1.2rem; padding: 1rem 2rem; background-color: #0070ba; display: inline-flex; align-items: center; gap: 0.5rem;" onclick="showThanks()">
            <img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/PP_logo_h_100x26.png" alt="PayPal" height="20">
            Faire un don gracieux de 1€
        </a>
    </div>

    <div id="thanksSection" style="display: none;">
        <img src="{{ asset('resources/views/img_website/merci-merci-beaucoup.gif') }}" alt="Merci beaucoup" style="max-width: 100%; margin: 2rem 0;">
        <p style="font-size: 1.3rem; color: #2ecc71;">
            Mes plus sincères remerciements, Noble Bienfaiteur !
        </p>
    </div>
</div>

<script>
function showThanks() {
    // Attendre que l'utilisateur revienne de PayPal
    setTimeout(() => {
        document.getElementById('donationSection').style.display = 'none';
        document.getElementById('thanksSection').style.display = 'block';
    }, 1000);
}
</script>
@endsection