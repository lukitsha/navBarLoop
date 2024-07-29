import React from 'react';
import ReactDOM from 'react-dom';
import App from './App';

const rootElement = document.getElementById('root');
if (rootElement) {
  ReactDOM.createRoot(rootElement).render(<App />);
} else {
  console.error("Root element not found");
}
