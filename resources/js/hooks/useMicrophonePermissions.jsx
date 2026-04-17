import { useState, useEffect, useCallback } from 'react';

const useMicrophonePermissions = () => {
    const [status, setStatus] = useState('loading'); // 'granted', 'denied', 'prompt', 'loading'
    const [errorMessage, setErrorMessage] = useState(null);

    const checkPermission = useCallback(async () => {
        try {
            // Navigator permissions API (Most modern browsers)
            if (navigator.permissions && navigator.permissions.query) {
                const result = await navigator.permissions.query({ name: 'microphone' });
                setStatus(result.state);

                result.onchange = () => {
                    setStatus(result.state);
                };
            } else {
                // Fallback si la API query no está disponible
                setStatus('prompt');
            }
        } catch (error) {
            console.error('Error checking microphone permissions:', error);
            setStatus('prompt');
        }
    }, []);

    useEffect(() => {
        checkPermission();
    }, [checkPermission]);

    const requestPermissions = useCallback(async () => {
        setStatus('loading');
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            // Si tiene éxito, el permiso se otorga implícitamente
            setStatus('granted');
            setErrorMessage(null);
            
            // Cerrar el stream inmediatamente solo era para pedir permiso
            stream.getTracks().forEach(track => track.stop());
            return true;
        } catch (error) {
            console.error('Permission request failed:', error);
            setStatus('denied');
            
            if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                setErrorMessage(
                    'El acceso al micrófono fue denegado. Para activarlo, ve a los Ajustes de tu navegador/dispositivo, busca Consultia y habilita el micrófono manualmente.'
                );
            } else {
                setErrorMessage('Ocurrió un error al intentar acceder al micrófono.');
            }
            return false;
        }
    }, []);

    return {
        status,
        loading: status === 'loading',
        isGranted: status === 'granted',
        isDenied: status === 'denied',
        canPrompt: status === 'prompt',
        errorMessage,
        requestPermissions,
        refreshStatus: checkPermission
    };
};

export default useMicrophonePermissions;
