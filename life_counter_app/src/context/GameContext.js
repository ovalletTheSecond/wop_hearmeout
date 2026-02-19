import React, { createContext, useContext, useReducer, useEffect } from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';

const STARTING_HP = 20;
const STORAGE_KEY_HISTORY = '@life_counter_history';

const GameContext = createContext(null);

const createPlayer = (id, name = '', deck = '') => ({
  id,
  name: name || `Joueur ${id}`,
  deck: deck || '',
  hp: STARTING_HP,
});

const initialState = {
  players: [createPlayer(1), createPlayer(2)],
  nextId: 3,
  history: [],
  gameActive: true,
};

function reducer(state, action) {
  switch (action.type) {
    case 'CHANGE_HP': {
      return {
        ...state,
        players: state.players.map((p) =>
          p.id === action.playerId ? { ...p, hp: p.hp + action.delta } : p
        ),
      };
    }
    case 'SET_HP': {
      return {
        ...state,
        players: state.players.map((p) =>
          p.id === action.playerId ? { ...p, hp: action.hp } : p
        ),
      };
    }
    case 'UPDATE_PLAYER': {
      return {
        ...state,
        players: state.players.map((p) =>
          p.id === action.playerId ? { ...p, ...action.updates } : p
        ),
      };
    }
    case 'ADD_PLAYER': {
      const newPlayer = createPlayer(state.nextId);
      return {
        ...state,
        players: [...state.players, newPlayer],
        nextId: state.nextId + 1,
      };
    }
    case 'REMOVE_PLAYER': {
      if (state.players.length <= 1) return state;
      return {
        ...state,
        players: state.players.filter((p) => p.id !== action.playerId),
      };
    }
    case 'RESET_GAME': {
      return {
        ...state,
        players: state.players.map((p) => ({ ...p, hp: STARTING_HP })),
        gameActive: true,
      };
    }
    case 'END_GAME': {
      const battle = {
        id: Date.now(),
        date: new Date().toISOString(),
        players: state.players.map((p) => ({ ...p })),
        winnerId: action.winnerId,
        winnerName: action.winnerName,
        winnerDeck: action.winnerDeck,
      };
      const updatedHistory = [battle, ...state.history];
      return {
        ...state,
        history: updatedHistory,
        gameActive: false,
      };
    }
    case 'LOAD_HISTORY': {
      return { ...state, history: action.history };
    }
    default:
      return state;
  }
}

export function GameProvider({ children }) {
  const [state, dispatch] = useReducer(reducer, initialState);

  useEffect(() => {
    AsyncStorage.getItem(STORAGE_KEY_HISTORY).then((raw) => {
      if (raw) {
        try {
          const history = JSON.parse(raw);
          dispatch({ type: 'LOAD_HISTORY', history });
        } catch (_e) {
          console.warn('Failed to parse stored history:', _e);
        }
      }
    });
  }, []);

  useEffect(() => {
    AsyncStorage.setItem(STORAGE_KEY_HISTORY, JSON.stringify(state.history));
  }, [state.history]);

  return (
    <GameContext.Provider value={{ state, dispatch }}>
      {children}
    </GameContext.Provider>
  );
}

export function useGame() {
  const ctx = useContext(GameContext);
  if (!ctx) throw new Error('useGame must be used within GameProvider');
  return ctx;
}

export { STARTING_HP };
