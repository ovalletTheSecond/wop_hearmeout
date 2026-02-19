# ⚔️ Life Counter — Compteur de Points de Vie

Application mobile React Native (Expo) pour compter les points de vie dans les jeux de cartes (Magic: The Gathering, etc.).

## Fonctionnalités

- 🎮 **Écran scindé** pour 2 joueurs (extensible jusqu'à N joueurs)
- ➕➖ Boutons `+` / `−` pour chaque joueur
- ⚡ **Raccourcis rapides** : ±1, ±2, ±3, ±5, ±7
- ☠️ **Points de vie en rouge** quand un joueur est mort (≤ 0)
- 👥 **Menu joueurs** (coin supérieur droit) : ajouter/retirer des joueurs
- ✏️ **Pseudo et deck** personnalisables par joueur (appuyer sur le nom)
- ⚑ **Fin de partie** : sélectionner le vainqueur avec son deck
- 📜 **Historique** des combats enregistrés (persistant)
- 📊 **Statistiques** : victoires/défaites par joueur et par deck
- 🔄 Réinitialisation rapide (tous à 20 PV)

## Démarrage rapide

### Prérequis

- [Node.js](https://nodejs.org/) 18+
- [Expo CLI](https://docs.expo.dev/get-started/installation/)
- [Expo Go](https://expo.dev/go) sur votre téléphone (iOS ou Android)

### Installation

```bash
cd life_counter_app
npm install
npm start
```

Scannez le QR code avec Expo Go pour lancer l'application sur votre téléphone.

### Développement Android/iOS

```bash
# Android
npm run android

# iOS (macOS uniquement)
npm run ios
```

## Structure du projet

```
life_counter_app/
├── App.js                    # Point d'entrée, navigation
├── app.json                  # Configuration Expo
├── package.json
└── src/
    ├── context/
    │   └── GameContext.js    # État global (useReducer + AsyncStorage)
    ├── screens/
    │   ├── GameScreen.js     # Écran principal
    │   ├── HistoryScreen.js  # Historique des combats
    │   └── StatsScreen.js    # Statistiques joueurs
    └── components/
        ├── PlayerCard.js     # Carte joueur (HP, +/-, édition)
        ├── PlayerMenu.js     # Menu gestion joueurs
        └── EndGameModal.js   # Modal fin de partie
```

## Technologies

- **React Native** (Expo ~51)
- **React Navigation** (navigation entre écrans)
- **AsyncStorage** (persistence locale des données)
- **React Context + useReducer** (gestion d'état)

## Points de vie par défaut

Les parties démarrent à **20 points de vie** par joueur (configurable dans `GameContext.js` via la constante `STARTING_HP`).
