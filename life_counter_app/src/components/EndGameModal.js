import React, { useState } from 'react';
import {
  View,
  Text,
  TouchableOpacity,
  TextInput,
  StyleSheet,
  Modal,
  ScrollView,
} from 'react-native';
import { useGame } from '../context/GameContext';

export default function EndGameModal({ visible, onClose, onRestart }) {
  const { state, dispatch } = useGame();
  const [selectedWinnerId, setSelectedWinnerId] = useState(null);
  const [winnerDeck, setWinnerDeck] = useState('');

  const selectedWinner = state.players.find((p) => p.id === selectedWinnerId);

  const handleConfirm = () => {
    if (!selectedWinnerId) return;
    const winner = state.players.find((p) => p.id === selectedWinnerId);
    dispatch({
      type: 'END_GAME',
      winnerId: selectedWinnerId,
      winnerName: winner.name,
      winnerDeck: winnerDeck || winner.deck || '',
    });
    onClose();
    onRestart();
  };

  return (
    <Modal
      visible={visible}
      transparent
      animationType="fade"
      onRequestClose={onClose}
    >
      <View style={styles.overlay}>
        <View style={styles.container}>
          <Text style={styles.title}>⚔️ Fin de Partie</Text>
          <Text style={styles.subtitle}>Qui a remporté la victoire ?</Text>

          <ScrollView style={styles.playersList}>
            {state.players.map((player) => (
              <TouchableOpacity
                key={player.id}
                style={[
                  styles.playerOption,
                  selectedWinnerId === player.id && styles.playerOptionSelected,
                ]}
                onPress={() => {
                  setSelectedWinnerId(player.id);
                  setWinnerDeck(player.deck || '');
                }}
              >
                <Text
                  style={[
                    styles.playerOptionText,
                    selectedWinnerId === player.id &&
                      styles.playerOptionTextSelected,
                  ]}
                >
                  👑 {player.name}
                </Text>
                {player.deck ? (
                  <Text style={styles.playerOptionDeck}>🃏 {player.deck}</Text>
                ) : null}
                <Text style={styles.playerOptionHp}>
                  {player.hp > 0
                    ? `${player.hp} PV restants`
                    : '☠ Éliminé'}
                </Text>
              </TouchableOpacity>
            ))}
          </ScrollView>

          {selectedWinnerId && (
            <View style={styles.deckSection}>
              <Text style={styles.inputLabel}>
                Deck de {selectedWinner?.name}
              </Text>
              <TextInput
                style={styles.input}
                value={winnerDeck}
                onChangeText={setWinnerDeck}
                placeholder="Nom du deck gagnant"
                placeholderTextColor="#666"
              />
            </View>
          )}

          <View style={styles.buttons}>
            <TouchableOpacity
              style={[styles.btn, styles.btnCancel]}
              onPress={onClose}
            >
              <Text style={styles.btnText}>Annuler</Text>
            </TouchableOpacity>
            <TouchableOpacity
              style={[
                styles.btn,
                styles.btnConfirm,
                !selectedWinnerId && styles.btnDisabled,
              ]}
              onPress={handleConfirm}
              disabled={!selectedWinnerId}
            >
              <Text style={styles.btnText}>Confirmer</Text>
            </TouchableOpacity>
          </View>
        </View>
      </View>
    </Modal>
  );
}

const styles = StyleSheet.create({
  overlay: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.85)',
    justifyContent: 'center',
    alignItems: 'center',
    padding: 20,
  },
  container: {
    backgroundColor: '#1a1a2e',
    borderRadius: 20,
    padding: 24,
    width: '100%',
    maxWidth: 440,
    borderWidth: 2,
    borderColor: '#e94560',
    maxHeight: '85%',
  },
  title: {
    color: '#e94560',
    fontSize: 24,
    fontWeight: 'bold',
    textAlign: 'center',
    marginBottom: 6,
  },
  subtitle: {
    color: '#a0a0c0',
    fontSize: 15,
    textAlign: 'center',
    marginBottom: 16,
  },
  playersList: {
    maxHeight: 280,
  },
  playerOption: {
    backgroundColor: '#0f0f1a',
    borderRadius: 12,
    padding: 14,
    marginBottom: 10,
    borderWidth: 2,
    borderColor: '#0f3460',
  },
  playerOptionSelected: {
    borderColor: '#e94560',
    backgroundColor: '#2d0a1a',
  },
  playerOptionText: {
    color: '#e0e0e0',
    fontSize: 17,
    fontWeight: 'bold',
  },
  playerOptionTextSelected: {
    color: '#e94560',
  },
  playerOptionDeck: {
    color: '#a0a0c0',
    fontSize: 13,
    marginTop: 3,
  },
  playerOptionHp: {
    color: '#666',
    fontSize: 12,
    marginTop: 3,
  },
  deckSection: {
    marginTop: 12,
  },
  inputLabel: {
    color: '#a0a0c0',
    fontSize: 14,
    marginBottom: 6,
  },
  input: {
    backgroundColor: '#0f0f1a',
    color: '#e0e0e0',
    borderRadius: 8,
    padding: 12,
    fontSize: 16,
    borderWidth: 1,
    borderColor: '#0f3460',
  },
  buttons: {
    flexDirection: 'row',
    gap: 12,
    marginTop: 20,
  },
  btn: {
    flex: 1,
    padding: 14,
    borderRadius: 10,
    alignItems: 'center',
  },
  btnCancel: {
    backgroundColor: '#333',
  },
  btnConfirm: {
    backgroundColor: '#e94560',
  },
  btnDisabled: {
    opacity: 0.4,
  },
  btnText: {
    color: '#fff',
    fontSize: 15,
    fontWeight: 'bold',
  },
});
