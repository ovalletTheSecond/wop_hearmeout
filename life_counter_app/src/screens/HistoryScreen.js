import React from 'react';
import {
  View,
  Text,
  FlatList,
  StyleSheet,
  TouchableOpacity,
  Alert,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { useGame } from '../context/GameContext';

function formatDate(isoString) {
  const d = new Date(isoString);
  return d.toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
}

function BattleCard({ battle }) {
  return (
    <View style={styles.card}>
      <View style={styles.cardHeader}>
        <Text style={styles.date}>{formatDate(battle.date)}</Text>
        <Text style={styles.trophy}>🏆</Text>
      </View>

      <View style={styles.winnerRow}>
        <Text style={styles.winnerLabel}>Vainqueur : </Text>
        <Text style={styles.winnerName}>{battle.winnerName}</Text>
        {battle.winnerDeck ? (
          <Text style={styles.winnerDeck}> 🃏 {battle.winnerDeck}</Text>
        ) : null}
      </View>

      <View style={styles.playersList}>
        {battle.players.map((p) => (
          <View
            key={p.id}
            style={[
              styles.playerRow,
              p.id === battle.winnerId && styles.playerRowWinner,
            ]}
          >
            <Text
              style={[
                styles.playerName,
                p.id === battle.winnerId && styles.playerNameWinner,
              ]}
            >
              {p.id === battle.winnerId ? '👑 ' : '💀 '}
              {p.name}
            </Text>
            {p.deck ? (
              <Text style={styles.playerDeck}>🃏 {p.deck}</Text>
            ) : null}
            <Text style={styles.playerHp}>
              {p.hp > 0 ? `${p.hp} PV` : 'Éliminé'}
            </Text>
          </View>
        ))}
      </View>
    </View>
  );
}

export default function HistoryScreen() {
  const { state, dispatch } = useGame();

  const clearHistory = () => {
    Alert.alert(
      'Effacer l\'historique',
      'Voulez-vous vraiment supprimer tout l\'historique des combats ?',
      [
        { text: 'Annuler', style: 'cancel' },
        {
          text: 'Supprimer',
          style: 'destructive',
          onPress: () => dispatch({ type: 'LOAD_HISTORY', history: [] }),
        },
      ]
    );
  };

  return (
    <SafeAreaView style={styles.container} edges={['bottom']}>
      {state.history.length === 0 ? (
        <View style={styles.emptyContainer}>
          <Text style={styles.emptyText}>📜</Text>
          <Text style={styles.emptyTitle}>Aucun combat enregistré</Text>
          <Text style={styles.emptySubtitle}>
            Utilisez le bouton "Fin de partie" pour enregistrer vos combats.
          </Text>
        </View>
      ) : (
        <>
          <FlatList
            data={state.history}
            keyExtractor={(item) => String(item.id)}
            renderItem={({ item }) => <BattleCard battle={item} />}
            contentContainerStyle={styles.list}
          />
          <TouchableOpacity style={styles.clearBtn} onPress={clearHistory}>
            <Text style={styles.clearBtnText}>🗑 Effacer l'historique</Text>
          </TouchableOpacity>
        </>
      )}
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#1a1a2e',
  },
  list: {
    padding: 16,
    gap: 12,
  },
  card: {
    backgroundColor: '#16213e',
    borderRadius: 14,
    padding: 16,
    borderWidth: 1,
    borderColor: '#0f3460',
  },
  cardHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 10,
  },
  date: {
    color: '#888',
    fontSize: 13,
  },
  trophy: {
    fontSize: 18,
  },
  winnerRow: {
    flexDirection: 'row',
    alignItems: 'center',
    flexWrap: 'wrap',
    marginBottom: 10,
  },
  winnerLabel: {
    color: '#a0a0c0',
    fontSize: 14,
  },
  winnerName: {
    color: '#e94560',
    fontSize: 15,
    fontWeight: 'bold',
  },
  winnerDeck: {
    color: '#a0a0c0',
    fontSize: 13,
  },
  playersList: {
    gap: 6,
  },
  playerRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
    paddingVertical: 4,
    paddingHorizontal: 8,
    borderRadius: 8,
    backgroundColor: '#0f0f1a',
    flexWrap: 'wrap',
  },
  playerRowWinner: {
    backgroundColor: '#2d0a1a',
    borderWidth: 1,
    borderColor: '#e94560',
  },
  playerName: {
    color: '#888',
    fontSize: 14,
    flex: 1,
  },
  playerNameWinner: {
    color: '#e94560',
    fontWeight: 'bold',
  },
  playerDeck: {
    color: '#666',
    fontSize: 12,
  },
  playerHp: {
    color: '#555',
    fontSize: 12,
  },
  emptyContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    padding: 32,
  },
  emptyText: {
    fontSize: 56,
    marginBottom: 16,
  },
  emptyTitle: {
    color: '#e0e0e0',
    fontSize: 20,
    fontWeight: 'bold',
    marginBottom: 8,
    textAlign: 'center',
  },
  emptySubtitle: {
    color: '#666',
    fontSize: 14,
    textAlign: 'center',
    lineHeight: 22,
  },
  clearBtn: {
    margin: 16,
    padding: 14,
    backgroundColor: '#2d0a0a',
    borderRadius: 10,
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#8b0000',
  },
  clearBtnText: {
    color: '#ff6666',
    fontSize: 14,
    fontWeight: '600',
  },
});
