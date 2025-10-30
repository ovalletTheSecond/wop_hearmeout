import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:scan_clean_food/models/product.dart';

class OpenFoodFactsService {
  static const String baseUrl = 'https://world.openfoodfacts.org/api/v0';

  /// Fetches product information from Open Food Facts API
  Future<Product?> getProduct(String barcode) async {
    try {
      final url = Uri.parse('$baseUrl/product/$barcode.json');
      final response = await http.get(url);

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        
        if (data['status'] == 1 && data['product'] != null) {
          final product = data['product'];
          
          // Extract product information
          final name = product['product_name'] ?? 'Produit inconnu';
          final brands = product['brands'] ?? 'Marque inconnue';
          final imageUrl = product['image_url'];
          final ingredientsText = product['ingredients_text'] ?? '';
          
          // Detect insect ingredients
          final detectedInsectIngredients = _detectInsectIngredients(ingredientsText);
          
          return Product(
            barcode: barcode,
            name: name,
            brands: brands,
            imageUrl: imageUrl,
            ingredientsText: ingredientsText,
            detectedInsectIngredients: detectedInsectIngredients,
            scanDate: DateTime.now(),
          );
        }
      }
      
      return null;
    } catch (e) {
      print('Error fetching product: $e');
      return null;
    }
  }

  /// Detects insect-based ingredients in the ingredients text
  List<String> _detectInsectIngredients(String ingredientsText) {
    if (ingredientsText.isEmpty) return [];
    
    // List of keywords to detect (French and English)
    final insectKeywords = [
      'insecte',
      'insect',
      'grillon',
      'cricket',
      'poudre de grillon',
      'cricket powder',
      'farine de grillon',
      'cricket flour',
      'tenebrio',
      'vers de farine',
      'mealworm',
      'ver de farine',
      'mouche soldat noire',
      'black soldier fly',
      'hermetia illucens',
      'protéine d\'insecte',
      'insect protein',
      'proteine d\'insecte',
      'farine d\'insecte',
      'insect flour',
      'alfitobius diaperinus',
      'lesser mealworm',
      'acheta domesticus',
      'house cricket',
      'locusta migratoria',
      'locust',
      'criquet',
      'larve',
      'larva',
      'larvae',
      'poudre d\'insecte',
      'insect powder',
    ];
    
    final detectedIngredients = <String>[];
    final lowerIngredients = ingredientsText.toLowerCase();
    
    for (final keyword in insectKeywords) {
      if (lowerIngredients.contains(keyword.toLowerCase())) {
        if (!detectedIngredients.contains(keyword)) {
          detectedIngredients.add(keyword);
        }
      }
    }
    
    return detectedIngredients;
  }
}
