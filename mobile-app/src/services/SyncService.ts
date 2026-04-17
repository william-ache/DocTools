import { Storage } from '@ionic/storage-angular';
import { Network } from '@capacitor/network';
import axios from 'axios';
import { v4 as uuidv4 } from 'uuid';

export interface SyncableRecord {
    id: string; // UUID
    type: 'patient' | 'consultation';
    data: any;
    status: 'synced' | 'pending' | 'deleted';
    updated_at: number;
}

export class SyncService {
    private storage: Storage | null = null;
    private apiUrl = 'https://api.consultia.com/v1';

    constructor(private storageInstance: Storage) {
        this.init();
    }

    async init() {
        this.storage = await this.storageInstance.create();
        this.listenToNetwork();
    }

    private listenToNetwork() {
        Network.addListener('networkStatusChange', (status) => {
            if (status.connected) {
                console.log('Online! Syncing pending data...');
                this.syncPendingData();
            }
        });
    }

    /**
     * Guarda un registro localmente y lo marca para sincronización.
     */
    async saveData(type: 'patient' | 'consultation', data: any) {
        // Generar UUID localmente si no existe para evitar conflictos
        if (!data.id) data.id = uuidv4();

        const record: SyncableRecord = {
            id: data.id,
            type,
            data,
            status: 'pending',
            updated_at: Date.now()
        };

        await this.storage?.set(`sync_${record.id}`, record);
        
        // Intentar sincronizar inmediatamente si hay red
        const status = await Network.getStatus();
        if (status.connected) {
            this.syncSingleRecord(record);
        }
    }

    /**
     * Sincroniza todos los registros pendientes con el backend Laravel.
     */
    async syncPendingData() {
        const keys = await this.storage?.keys() || [];
        for (const key of keys) {
            if (key.startsWith('sync_')) {
                const record: SyncableRecord = await this.storage?.get(key);
                if (record.status === 'pending') {
                    await this.syncSingleRecord(record);
                }
            }
        }
    }

    private async syncSingleRecord(record: SyncableRecord) {
        try {
            const endpoint = record.type === 'patient' ? '/patients' : '/consultations';
            
            // Usamos PUT (upsert) para manejar conflictos de IDs existentes
            await axios.put(`${this.apiUrl}${endpoint}/${record.id}`, record.data);

            // Marcar como sincronizado localmente
            record.status = 'synced';
            await this.storage?.set(`sync_${record.id}`, record);
            console.log(`Record ${record.id} synced successfully.`);
        } catch (error) {
            console.error(`Failed to sync record ${record.id}:`, error);
        }
    }

    /**
     * Recupera datos locales (Inmediato)
     */
    async getLocalData(type: 'patient' | 'consultation') {
        const keys = await this.storage?.keys() || [];
        const results = [];
        for (const key of keys) {
            if (key.startsWith('sync_')) {
                const record: SyncableRecord = await this.storage?.get(key);
                if (record.type === type && record.status !== 'deleted') {
                    results.push(record.data);
                }
            }
        }
        return results;
    }
}
