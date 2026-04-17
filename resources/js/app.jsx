import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import DocIaMicButton from './components/DocIaMicButton.jsx';

const micContainer = document.getElementById('doc-ia-global-mic');
if (micContainer) {
    const root = createRoot(micContainer);
    root.render(<DocIaMicButton accessKey={import.meta.env.VITE_PICOVOICE_ACCESS_KEY} />);
}
