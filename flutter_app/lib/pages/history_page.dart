import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:scan_clean_food/providers/history_provider.dart';
import 'package:scan_clean_food/widgets/history_list_item.dart';

class HistoryPage extends StatelessWidget {
  const HistoryPage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Historique des scans'),
        centerTitle: true,
        actions: [
          Consumer<HistoryProvider>(
            builder: (context, historyProvider, child) {
              if (historyProvider.history.isEmpty) {
                return const SizedBox.shrink();
              }
              return IconButton(
                icon: const Icon(Icons.delete_sweep),
                onPressed: () {
                  _showClearHistoryDialog(context, historyProvider);
                },
                tooltip: 'Effacer l\'historique',
              );
            },
          ),
        ],
      ),
      body: Consumer<HistoryProvider>(
        builder: (context, historyProvider, child) {
          if (historyProvider.history.isEmpty) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(
                    Icons.history,
                    size: 80,
                    color: Theme.of(context).colorScheme.outline,
                  ),
                  const SizedBox(height: 24),
                  Text(
                    'Aucun historique',
                    style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                      color: Theme.of(context).colorScheme.outline,
                    ),
                  ),
                  const SizedBox(height: 16),
                  Text(
                    'Les produits scannés apparaîtront ici',
                    style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                      color: Theme.of(context).colorScheme.outline,
                    ),
                  ),
                ],
              ),
            );
          }

          return ListView.builder(
            itemCount: historyProvider.history.length,
            padding: const EdgeInsets.all(8),
            itemBuilder: (context, index) {
              final product = historyProvider.history[index];
              return Dismissible(
                key: Key(product.barcode + product.scanDate.toString()),
                direction: DismissDirection.endToStart,
                background: Container(
                  alignment: Alignment.centerRight,
                  padding: const EdgeInsets.only(right: 20),
                  color: Colors.red,
                  child: const Icon(Icons.delete, color: Colors.white, size: 32),
                ),
                onDismissed: (direction) {
                  historyProvider.removeFromHistory(index);
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(
                      content: Text('${product.name} supprimé de l\'historique'),
                      action: SnackBarAction(
                        label: 'Annuler',
                        onPressed: () {
                          // Could implement undo functionality
                        },
                      ),
                    ),
                  );
                },
                child: HistoryListItem(
                  product: product,
                  onTap: () {
                    _showProductDetails(context, product);
                  },
                ),
              );
            },
          );
        },
      ),
    );
  }

  void _showClearHistoryDialog(BuildContext context, HistoryProvider historyProvider) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Effacer l\'historique'),
        content: const Text(
          'Êtes-vous sûr de vouloir supprimer tout l\'historique des scans ?',
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Annuler'),
          ),
          TextButton(
            onPressed: () {
              historyProvider.clearHistory();
              Navigator.pop(context);
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(
                  content: Text('Historique effacé'),
                ),
              );
            },
            child: const Text('Effacer', style: TextStyle(color: Colors.red)),
          ),
        ],
      ),
    );
  }

  void _showProductDetails(BuildContext context, product) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      builder: (context) => DraggableScrollableSheet(
        initialChildSize: 0.7,
        minChildSize: 0.5,
        maxChildSize: 0.95,
        expand: false,
        builder: (context, scrollController) {
          return Container(
            padding: const EdgeInsets.all(16),
            child: ListView(
              controller: scrollController,
              children: [
                Center(
                  child: Container(
                    width: 40,
                    height: 4,
                    margin: const EdgeInsets.only(bottom: 20),
                    decoration: BoxDecoration(
                      color: Colors.grey.shade300,
                      borderRadius: BorderRadius.circular(2),
                    ),
                  ),
                ),
                Text(
                  product.name,
                  style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 8),
                Text(
                  product.brands,
                  style: Theme.of(context).textTheme.titleMedium,
                ),
                const SizedBox(height: 16),
                
                if (product.imageUrl != null)
                  ClipRRect(
                    borderRadius: BorderRadius.circular(8),
                    child: Image.network(
                      product.imageUrl!,
                      height: 200,
                      fit: BoxFit.cover,
                      errorBuilder: (context, error, stackTrace) {
                        return Container(
                          height: 200,
                          color: Colors.grey.shade200,
                          child: const Icon(Icons.image_not_supported, size: 64),
                        );
                      },
                    ),
                  ),
                
                const SizedBox(height: 16),
                
                Card(
                  color: product.hasInsectIngredients 
                      ? Colors.red.shade50 
                      : Colors.green.shade50,
                  child: Padding(
                    padding: const EdgeInsets.all(16),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: [
                            Icon(
                              product.hasInsectIngredients 
                                  ? Icons.warning 
                                  : Icons.check_circle,
                              color: product.hasInsectIngredients 
                                  ? Colors.red 
                                  : Colors.green,
                            ),
                            const SizedBox(width: 8),
                            Expanded(
                              child: Text(
                                product.hasInsectIngredients
                                    ? 'Contient des ingrédients d\'origine insecte'
                                    : 'Aucun ingrédient d\'origine insecte',
                                style: TextStyle(
                                  fontWeight: FontWeight.bold,
                                  color: product.hasInsectIngredients 
                                      ? Colors.red.shade900 
                                      : Colors.green.shade900,
                                ),
                              ),
                            ),
                          ],
                        ),
                        if (product.hasInsectIngredients) ...[
                          const SizedBox(height: 12),
                          ...product.detectedInsectIngredients.map(
                            (ingredient) => Padding(
                              padding: const EdgeInsets.symmetric(vertical: 4),
                              child: Row(
                                children: [
                                  Icon(Icons.bug_report, 
                                      size: 16, color: Colors.red.shade700),
                                  const SizedBox(width: 8),
                                  Expanded(
                                    child: Text(
                                      ingredient,
                                      style: TextStyle(color: Colors.red.shade900),
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          ),
                        ],
                      ],
                    ),
                  ),
                ),
                
                if (product.ingredientsText.isNotEmpty) ...[
                  const SizedBox(height: 16),
                  Text(
                    'Ingrédients',
                    style: Theme.of(context).textTheme.titleMedium?.copyWith(
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(product.ingredientsText),
                ],
                
                const SizedBox(height: 16),
                Text(
                  'Code-barres: ${product.barcode}',
                  style: Theme.of(context).textTheme.bodySmall,
                ),
                const SizedBox(height: 40),
              ],
            ),
          );
        },
      ),
    );
  }
}
