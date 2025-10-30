# Guide de déploiement - Scan Clean Food

Ce guide explique comment compiler et déployer l'application Scan Clean Food sur le Google Play Store.

## Prérequis

### Outils nécessaires
- Flutter SDK 3.5.0 ou supérieur
- Android Studio ou VS Code avec Flutter
- JDK 17 ou supérieur
- Android SDK (API 34)
- Compte développeur Google Play (99$ par an)

### Vérification de l'environnement
```bash
flutter doctor -v
```

Assurez-vous que toutes les dépendances sont installées.

## Étape 1 : Configuration du projet

### 1.1 Cloner et installer les dépendances
```bash
git clone https://github.com/ovalletTheSecond/wop_hearmeout.git
cd wop_hearmeout/flutter_app
flutter pub get
```

### 1.2 Vérifier la configuration Android
Vérifiez que les fichiers suivants sont correctement configurés :
- `android/app/build.gradle` : packageId, versions
- `android/app/src/main/AndroidManifest.xml` : permissions, nom de l'app

## Étape 2 : Générer une clé de signature

### 2.1 Créer un keystore
```bash
keytool -genkey -v -keystore ~/upload-keystore.jks \
  -keyalg RSA -keysize 2048 -validity 10000 \
  -alias upload \
  -storepass [MOT_DE_PASSE] \
  -keypass [MOT_DE_PASSE]
```

**Important** : Conservez ce fichier et les mots de passe en sécurité !

### 2.2 Créer key.properties
Créez le fichier `android/key.properties` :
```properties
storePassword=[MOT_DE_PASSE_STORE]
keyPassword=[MOT_DE_PASSE_KEY]
keyAlias=upload
storeFile=/path/to/upload-keystore.jks
```

**Note** : Ce fichier est dans `.gitignore` et ne doit JAMAIS être commité.

### 2.3 Mettre à jour build.gradle
Dans `android/app/build.gradle`, avant `android {`, ajoutez :

```gradle
def keystoreProperties = new Properties()
def keystorePropertiesFile = rootProject.file('key.properties')
if (keystorePropertiesFile.exists()) {
    keystoreProperties.load(new FileInputStream(keystorePropertiesFile))
}
```

Puis dans le bloc `android`, modifiez `buildTypes` :

```gradle
signingConfigs {
    release {
        keyAlias keystoreProperties['keyAlias']
        keyPassword keystoreProperties['keyPassword']
        storeFile keystoreProperties['storeFile'] ? file(keystoreProperties['storeFile']) : null
        storePassword keystoreProperties['storePassword']
    }
}
buildTypes {
    release {
        signingConfig signingConfigs.release
        minifyEnabled true
        shrinkResources true
    }
}
```

## Étape 3 : Préparer l'icône de l'application

### 3.1 Créer les icônes
Créez une icône 512x512 avec :
- Fond vert clair (#8BC34A)
- Logo fourmi/insecte au centre
- Format PNG

### 3.2 Générer les ressources
Vous pouvez utiliser Android Asset Studio ou générer manuellement les tailles :
- mipmap-mdpi: 48x48
- mipmap-hdpi: 72x72
- mipmap-xhdpi: 96x96
- mipmap-xxhdpi: 144x144
- mipmap-xxxhdpi: 192x192

Placez-les dans `android/app/src/main/res/mipmap-*/ic_launcher.png`

Alternative - utilisez flutter_launcher_icons :
```yaml
# Dans pubspec.yaml
dev_dependencies:
  flutter_launcher_icons: ^0.13.1

flutter_launcher_icons:
  android: true
  image_path: "assets/icon/app_icon.png"
  adaptive_icon_background: "#8BC34A"
  adaptive_icon_foreground: "assets/icon/app_icon_foreground.png"
```

Puis exécutez :
```bash
flutter pub run flutter_launcher_icons
```

## Étape 4 : Compiler l'APK/AAB

### 4.1 Compiler un APK (pour test)
```bash
flutter build apk --release
```

L'APK sera dans `build/app/outputs/flutter-apk/app-release.apk`

### 4.2 Compiler un AAB (pour Play Store)
```bash
flutter build appbundle --release
```

L'AAB sera dans `build/app/outputs/bundle/release/app-release.aab`

**Important** : Le Play Store préfère les AAB aux APK.

## Étape 5 : Tester l'application

### 5.1 Installer l'APK sur un appareil
```bash
adb install build/app/outputs/flutter-apk/app-release.apk
```

### 5.2 Tests à effectuer
- [ ] Scanner fonctionne avec différents codes-barres
- [ ] Permissions caméra demandées correctement
- [ ] API Open Food Facts accessible
- [ ] Détection d'insectes fonctionne
- [ ] Historique sauvegarde et charge correctement
- [ ] Pas de crash au lancement
- [ ] Thème clair/sombre fonctionne
- [ ] Splash screen s'affiche
- [ ] Rotation d'écran gérée

## Étape 6 : Préparer les assets du Play Store

### 6.1 Captures d'écran requises
Préparez des captures d'écran pour :
- **Téléphone** : 2-8 captures (16:9, 1080x1920 min)
- **Tablette 7"** : 1-8 captures (optionnel)
- **Tablette 10"** : 1-8 captures (optionnel)

Utilisez un émulateur ou appareil réel pour capturer :
1. Écran d'accueil
2. Scanner en action
3. Résultat positif (sans insectes)
4. Résultat négatif (avec insectes)
5. Historique

### 6.2 Icône de l'application
- **Icône haute résolution** : 512x512 PNG
- Fond vert clair avec logo insecte

### 6.3 Bannière (feature graphic)
- **Dimensions** : 1024x500 pixels
- Format : JPG ou PNG 24-bit
- Inclure : Titre "Scan Clean Food" + slogan + visuel

### 6.4 Description

**Titre** (max 50 caractères) :
```
Scan Clean Food - Détecteur d'insectes
```

**Description courte** (max 80 caractères) :
```
Détectez les ingrédients d'origine insecte dans vos produits alimentaires
```

**Description longue** (max 4000 caractères) :
```
🐛 Scan Clean Food - Détecteur d'ingrédients d'origine insecte

Scannez simplement le code-barres d'un produit alimentaire pour savoir 
instantanément s'il contient des ingrédients d'origine insecte !

✨ FONCTIONNALITÉS

📱 Scanner de code-barres rapide et précis
🔍 Détection automatique des ingrédients d'origine insecte
📊 Informations détaillées sur chaque produit
📚 Historique de tous vos scans
🌙 Mode clair et sombre
🇫🇷 Interface en français

🦗 INGRÉDIENTS DÉTECTÉS

L'application détecte plus de 30 termes liés aux ingrédients d'origine insecte :
• Grillon / Cricket
• Vers de farine / Mealworm
• Mouche soldat noire
• Criquet / Locust
• Protéines et farines d'insectes
• Et bien d'autres...

🔒 CONFIDENTIALITÉ

• Aucune collecte de données personnelles
• Historique stocké localement sur votre appareil
• Utilise l'API publique Open Food Facts

🌍 BASE DE DONNÉES

Scan Clean Food utilise Open Food Facts, la plus grande base de données 
collaborative de produits alimentaires au monde avec plus de 2 millions 
de produits.

💡 POURQUOI SCAN CLEAN FOOD ?

Avec l'introduction récente d'ingrédients d'origine insecte dans 
l'alimentation européenne, de nombreux consommateurs souhaitent être 
informés de leur présence dans les produits. Scan Clean Food vous 
permet de faire un choix éclairé.

📱 SIMPLE ET RAPIDE

1. Scannez le code-barres
2. Consultez le résultat instantané
3. Prenez votre décision en connaissance de cause

Téléchargez maintenant et scannez vos produits !
```

## Étape 7 : Publier sur Google Play Store

### 7.1 Créer une application dans la Console Play
1. Allez sur https://play.google.com/console
2. Cliquez sur "Créer une application"
3. Remplissez :
   - Nom : "Scan Clean Food"
   - Langue par défaut : Français
   - Type : Application
   - Gratuit/Payant : Gratuit

### 7.2 Remplir la fiche du Store
Dans la Console Play, complétez :
- **Fiche du Store** : titre, description, captures d'écran, icône
- **Classification du contenu** : questionnaire
- **Public cible et contenu** : âge, confidentialité
- **Contact** : email de contact
- **Politique de confidentialité** : URL obligatoire

### 7.3 Préparer une version
1. Allez dans **Production** > **Créer une version**
2. Téléchargez le fichier AAB
3. Remplissez les notes de version (en français)
4. Vérifiez les avertissements et erreurs
5. Enregistrez

### 7.4 Soumettre pour révision
1. Vérifiez que tout est complété
2. Cliquez sur "Envoyer pour révision"
3. Attendez l'approbation (généralement 1-3 jours)

## Étape 8 : Mises à jour futures

### 8.1 Modifier la version
Dans `pubspec.yaml` :
```yaml
version: 1.0.1+2  # version+buildNumber
```

### 8.2 Compiler et publier
```bash
flutter build appbundle --release
```

Puis téléchargez dans la Console Play.

### 8.3 Notes de version
Rédigez toujours des notes de version claires en français :
```
Version 1.0.1
- Correction de bugs
- Amélioration de la détection
- Performance optimisée
```

## Checklist finale

Avant publication :
- [ ] Icône de l'application créée et intégrée
- [ ] Keystore généré et sécurisé
- [ ] AAB compilé et signé
- [ ] Application testée sur plusieurs appareils
- [ ] Captures d'écran créées
- [ ] Descriptions rédigées
- [ ] Politique de confidentialité publiée
- [ ] Email de contact configuré
- [ ] Classification du contenu complétée
- [ ] Version soumise pour révision

## Ressources

- [Documentation Flutter](https://docs.flutter.dev/deployment/android)
- [Console Google Play](https://play.google.com/console)
- [Open Food Facts](https://world.openfoodfacts.org/)
- [Recommandations Play Store](https://developer.android.com/distribute)

---

**Note** : Ce guide est fourni à titre informatif. Les procédures Google Play peuvent évoluer.
