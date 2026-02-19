import React from 'react';
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  Modal,
  ScrollView,
} from 'react-native';
import { useGame, STARTING_HP } from '../context/GameContext';

export default function PlayerMenu({ visible, onClose }) {
  const { state, dispatch } = useGame();

  const addPlayer = () => {
    dispatch({ type: 'ADD_PLAYER' });
  };

  const removePlayer = (id) => {
    if (state.players.length <= 1) return;
    dispatch({ type: 'REMOVE_PLAYER', playerId: id });
  };

  const resetAll = () => {
    dispatch({ type: 'RESET_GAME' });
    onClose();
  };

  return (
    <Modal
      visible={visible}
      transparent
      animationType="slide"
      onRequestClose={onClose}
    >
      <TouchableOpacity
        style={styles.overlay}
        activeOpacity={1}
        onPress={onClose}
      >
        <TouchableOpacity
          activeOpacity={1}
          style={styles.panel}
          onPress={(e) => e.stopPropagation()}
        >
          <Text style={styles.title}>⚙️ Gestion des joueurs</Text>

          <ScrollView style={styles.playerList}>
            {state.players.map((player) => (
              <View key={player.id} style={styles.playerRow}>
                <View style={styles.playerInfo}>
                  <Text style={styles.playerName}>{player.name}</Text>
                  <Text style={styles.playerDetails}>
                    {player.deck || 'Aucun deck'} — {player.hp} PV
                  </Text>
                </View>
                {state.players.length > 1 && (
                  <TouchableOpacity
                    style={styles.removeBtn}
                    onPress={() => removePlayer(player.id)}
                  >
                    <Text style={styles.removeBtnText}>✕</Text>
                  </TouchableOpacity>
                )}
              </View>
            ))}
          </ScrollView>

          <TouchableOpacity style={styles.addBtn} onPress={addPlayer}>
            <Text style={styles.addBtnText}>+ Ajouter un joueur</Text>
          </TouchableOpacity>

          <TouchableOpacity style={styles.resetBtn} onPress={resetAll}>
            <Text style={styles.resetBtnText}>
              🔄 Réinitialiser (tous à {STARTING_HP} PV)
            </Text>
          </TouchableOpacity>

          <TouchableOpacity style={styles.closeBtn} onPress={onClose}>
            <Text style={styles.closeBtnText}>Fermer</Text>
          </TouchableOpacity>
        </TouchableOpacity>
      </TouchableOpacity>
    </Modal>
  );
}

const styles = StyleSheet.create({
  overlay: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.6)',
    justifyContent: 'flex-start',
    alignItems: 'flex-end',
  },
  panel: {
    backgroundColor: '#1a1a2e',
    borderBottomLeftRadius: 20,
    borderBottomRightRadius: 0,
    borderTopLeftRadius: 0,
    borderTopRightRadius: 0,
    padding: 20,
    width: 280,
    maxHeight: '80%',
    marginTop: 60,
    borderWidth: 1,
    borderColor: '#0f3460',
    borderTopWidth: 0,
    borderRightWidth: 0,
  },
  title: {
    color: '#e94560',
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 16,
  },
  playerList: {
    maxHeight: 300,
    marginBottom: 12,
  },
  playerRow: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#0f0f1a',
    borderRadius: 10,
    padding: 12,
    marginBottom: 8,
    borderWidth: 1,
    borderColor: '#0f3460',
  },
  playerInfo: {
    flex: 1,
  },
  playerName: {
    color: '#e0e0e0',
    fontSize: 15,
    fontWeight: 'bold',
  },
  playerDetails: {
    color: '#888',
    fontSize: 12,
    marginTop: 2,
  },
  removeBtn: {
    backgroundColor: '#8b0000',
    borderRadius: 6,
    width: 28,
    height: 28,
    justifyContent: 'center',
    alignItems: 'center',
  },
  removeBtnText: {
    color: '#fff',
    fontSize: 14,
    fontWeight: 'bold',
  },
  addBtn: {
    backgroundColor: '#0f3460',
    borderRadius: 10,
    padding: 14,
    alignItems: 'center',
    marginBottom: 10,
  },
  addBtnText: {
    color: '#e0e0e0',
    fontSize: 15,
    fontWeight: 'bold',
  },
  resetBtn: {
    backgroundColor: '#1a4a2e',
    borderRadius: 10,
    padding: 12,
    alignItems: 'center',
    marginBottom: 10,
  },
  resetBtnText: {
    color: '#5dbb85',
    fontSize: 14,
    fontWeight: '600',
  },
  closeBtn: {
    padding: 12,
    alignItems: 'center',
  },
  closeBtnText: {
    color: '#888',
    fontSize: 14,
  },
});
