# Guide d'utilisation - Scan Clean Food

## Démarrage rapide

### 1. Installation de l'application

L'application Scan Clean Food peut être installée sur tout appareil Android 5.0 (API 21) ou supérieur.

#### Depuis le Google Play Store (à venir)
- Recherchez "Scan Clean Food" dans le Play Store
- Appuyez sur "Installer"
- Ouvrez l'application

#### Installation manuelle (développeurs)
```bash
flutter run --release
```

### 2. Première utilisation

Au premier lancement:
1. L'application vous demandera l'autorisation d'accéder à la caméra
2. Appuyez sur "Autoriser" pour pouvoir scanner les codes-barres

## Fonctionnalités principales

### Scanner un produit

1. **Depuis l'écran d'accueil**, appuyez sur le bouton "Scanner un produit"
2. **Pointez votre caméra** vers le code-barres du produit
3. **Cadrez le code-barres** dans le rectangle blanc
4. **Le scan est automatique** - pas besoin d'appuyer sur un bouton
5. **Attendez l'analyse** - l'application interroge la base de données Open Food Facts
6. **Consultez le résultat** :
   - ✅ **Vert** : Aucun ingrédient d'origine insecte détecté
   - ⚠️ **Rouge** : Des ingrédients d'origine insecte ont été détectés

### Comprendre les résultats

#### Résultat positif (sans insectes) 🟢
- Un grand cercle vert avec une coche
- Message : "Aucun ingrédient d'origine insecte détecté"
- Les informations du produit sont affichées

#### Résultat négatif (avec insectes) 🔴
- Un grand cercle rouge avec un symbole d'avertissement
- Message : "Ingrédients d'origine insecte détectés"
- Liste détaillée des ingrédients d'origine insecte trouvés
- Les informations du produit sont affichées

### Consulter l'historique

1. **Depuis l'écran d'accueil** :
   - Appuyez sur l'icône "⏱️" en haut à droite
   - OU appuyez sur "Voir tout" sous "Scans récents"

2. **Dans l'historique** :
   - Consultez tous vos scans précédents
   - Les produits avec insectes sont marqués d'un 🐛 rouge
   - Les produits sans insectes sont marqués d'un ✓ vert

3. **Actions disponibles** :
   - **Appuyez sur un produit** pour voir ses détails
   - **Glissez vers la gauche** pour supprimer un produit
   - **Icône poubelle** (en haut) pour effacer tout l'historique

### Fonctionnalités avancées

#### Flash de la caméra
Lors du scan, appuyez sur l'icône du flash en haut à droite pour activer/désactiver la lampe torche (utile dans les endroits sombres).

#### Gestion de l'historique
- L'historique conserve automatiquement vos 50 derniers scans
- Les scans sont sauvegardés localement sur votre appareil
- Aucune donnée n'est envoyée à des serveurs tiers

## Ingrédients détectés

L'application détecte les mots-clés suivants (français et anglais) :

### Ingrédients principaux
- 🦗 Grillon / Cricket
- 🐛 Vers de farine / Mealworm
- 🦟 Mouche soldat noire / Black soldier fly
- 🦗 Criquet / Locust

### Termes scientifiques
- Acheta domesticus (grillon domestique)
- Tenebrio molitor (ver de farine)
- Alfitobius diaperinus (petit ver de farine)
- Hermetia illucens (mouche soldat noire)
- Locusta migratoria (criquet migrateur)

### Formes transformées
- Poudre de grillon / Cricket powder
- Farine de grillon / Cricket flour
- Farine d'insecte / Insect flour
- Protéine d'insecte / Insect protein
- Poudre d'insecte / Insect powder

## Limitations et informations importantes

### Dépendance à Open Food Facts
- L'application utilise la base de données collaborative Open Food Facts
- Si un produit n'est pas dans la base, il ne sera pas trouvé
- La qualité des informations dépend des contributions des utilisateurs

### Détection des ingrédients
- La détection est basée sur des mots-clés dans la liste des ingrédients
- Les ingrédients doivent être explicitement mentionnés pour être détectés
- Certains produits peuvent utiliser des termes non standard

### Langue
- L'application est en français
- Elle détecte les termes en français ET en anglais
- Les produits internationaux sont supportés

### Connexion Internet
- Une connexion Internet est **requise** pour interroger l'API Open Food Facts
- L'historique fonctionne hors ligne

## FAQ

**Q: L'application ne trouve pas mon produit**
R: Le produit n'est peut-être pas encore dans la base Open Food Facts. Vous pouvez l'ajouter sur openfoodfacts.org.

**Q: Le scan ne fonctionne pas**
R: Assurez-vous que :
- Vous avez autorisé l'accès à la caméra
- Le code-barres est bien visible et éclairé
- Le code-barres n'est pas abîmé

**Q: L'application dit qu'il n'y a pas d'insectes, mais j'en vois dans les ingrédients**
R: Contactez-nous avec les détails du produit. Il se peut qu'un nouveau terme doive être ajouté à notre liste de détection.

**Q: Mes données sont-elles partagées ?**
R: Non, toutes les données (historique, préférences) sont stockées localement sur votre appareil.

**Q: Comment effacer mon historique ?**
R: Allez dans l'historique et appuyez sur l'icône poubelle en haut à droite.

## Support

Pour toute question ou problème :
- GitHub: https://github.com/ovalletTheSecond/wop_hearmeout
- Créez une issue pour rapporter un bug

## Contribuer

Si vous souhaitez contribuer :
- Ajoutez des produits à Open Food Facts
- Signalez des bugs ou suggérez des améliorations
- Partagez l'application avec vos amis !

---

**Version**: 1.0.0  
**Dernière mise à jour**: Octobre 2025
