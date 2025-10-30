import 'package:flutter/material.dart';
import 'package:scan_clean_food/models/product.dart';
import 'package:scan_clean_food/services/open_food_facts_service.dart';

class ScanProvider with ChangeNotifier {
  final OpenFoodFactsService _service = OpenFoodFactsService();
  
  Product? _currentProduct;
  bool _isLoading = false;
  String? _error;

  Product? get currentProduct => _currentProduct;
  bool get isLoading => _isLoading;
  String? get error => _error;

  /// Scans a barcode and fetches product information
  Future<void> scanBarcode(String barcode) async {
    _isLoading = true;
    _error = null;
    _currentProduct = null;
    notifyListeners();

    try {
      final product = await _service.getProduct(barcode);
      
      if (product != null) {
        _currentProduct = product;
        _error = null;
      } else {
        _error = 'Produit non trouvé dans la base de données';
      }
    } catch (e) {
      _error = 'Erreur lors de la récupération du produit: $e';
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  /// Clears the current scan result
  void clearResult() {
    _currentProduct = null;
    _error = null;
    notifyListeners();
  }
}
