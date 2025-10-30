# Scan Clean Food 🐛

Application mobile Android pour détecter les ingrédients d'origine insecte dans les produits alimentaires.

## Description

Scan Clean Food permet aux utilisateurs de scanner le code-barres d'un produit alimentaire et d'analyser automatiquement ses ingrédients pour détecter la présence d'ingrédients d'origine insecte (grillon, vers de farine, larves, etc.).

## Fonctionnalités

- ✅ Scanner de code-barres via la caméra
- ✅ Intégration avec l'API Open Food Facts
- ✅ Détection automatique des ingrédients d'origine insecte
- ✅ Historique des scans avec stockage local
- ✅ Interface Material Design 3
- ✅ Mode clair et sombre automatique
- ✅ Support Android 13+

## Technologies utilisées

- **Framework**: Flutter/Dart
- **Scanner**: mobile_scanner
- **Gestion d'état**: provider
- **Stockage local**: shared_preferences
- **API**: Open Food Facts
- **UI**: Material Design 3

## Installation

### Prérequis

- Flutter SDK (version 3.5.0 ou supérieure)
- Android Studio ou VS Code avec extensions Flutter
- Android SDK (API 21+)

### Configuration

1. Cloner le repository
```bash
git clone https://github.com/ovalletTheSecond/wop_hearmeout.git
cd wop_hearmeout/flutter_app
```

2. Installer les dépendances
```bash
flutter pub get
```

3. Lancer l'application
```bash
flutter run
```

## Configuration Android

- **Package name**: `com.scancleanfood.app`
- **Min SDK**: 21 (Android 5.0)
- **Target SDK**: 34 (Android 14)
- **Permissions**: Caméra, Internet

## Architecture

```
lib/
├── main.dart
├── models/
│   └── product.dart
├── services/
│   └── open_food_facts_service.dart
├── providers/
│   ├── scan_provider.dart
│   └── history_provider.dart
├── pages/
│   ├── home_page.dart
│   ├── scanner_page.dart
│   ├── result_page.dart
│   └── history_page.dart
└── widgets/
    └── history_list_item.dart
```

## Mots-clés détectés

L'application détecte les mots-clés suivants dans les ingrédients:
- insecte, insect
- grillon, cricket
- poudre de grillon, cricket powder
- farine de grillon, cricket flour
- tenebrio
- vers de farine, mealworm
- mouche soldat noire, black soldier fly
- protéine d'insecte, insect protein
- alfitobius diaperinus
- acheta domesticus
- locusta migratoria
- Et plus encore...

## Publication sur Google Play Store

L'application est prête pour être publiée sur le Google Play Store avec les configurations suivantes:

- **Nom**: Scan Clean Food – Détecteur d'ingrédients d'origine insecte
- **Package**: com.scancleanfood.app
- **Version**: 1.0.0+1
- **Langue principale**: Français

## License

MIT License

## Auteur

Développé pour le projet wop_hearmeout
