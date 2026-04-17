import React, { useState, useEffect } from 'react';
import axios from 'axios';

const Dashboard = () => {
    const [patients, setPatients] = useState([]);
    const [searchTerm, setSearchTerm] = useState('');
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [loading, setLoading] = useState(false);

    // Form State
    const [formData, setFormData] = useState({
        patient_id: '',
        diagnosis: '',
        treatment: '',
        notes: ''
    });

    useEffect(() => {
        fetchPatients();
    }, []);

    const fetchPatients = async () => {
        try {
            const response = await axios.get('/api/patients');
            setPatients(response.data);
        } catch (error) {
            console.error('Error fetching patients:', error);
        }
    };

    const handleSearch = (e) => setSearchTerm(e.target.value);

    const filteredPatients = patients.filter(p => 
        p.name.toLowerCase().includes(searchTerm.toLowerCase()) || 
        p.id_number.includes(searchTerm)
    );

    const handleSubmitConsultation = async (e) => {
        e.preventDefault();
        setLoading(true);
        try {
            await axios.post('/api/consultations', formData);
            alert('Consulta guardada con éxito');
            setIsModalOpen(false);
            setFormData({ patient_id: '', diagnosis: '', treatment: '', notes: '' });
        } catch (error) {
            alert('Error al guardar la consulta');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="flex h-screen bg-gray-50 font-sans antialiased">
            {/* Sidebar */}
            <aside className="w-64 bg-indigo-900 text-white flex flex-col shadow-2xl">
                <div className="p-8">
                    <h1 className="text-2xl font-black tracking-tighter italic">Consultia</h1>
                </div>
                <nav className="flex-1 px-4 space-y-2">
                    <a href="#" className="flex items-center gap-4 p-4 rounded-xl bg-indigo-800 font-bold">
                        <i className="fa-solid fa-chart-line"></i> Dashboard
                    </a>
                    <a href="#" className="flex items-center gap-4 p-4 rounded-xl hover:bg-indigo-800 transition-colors">
                        <i className="fa-solid fa-users"></i> Pacientes
                    </a>
                    <a href="#" className="flex items-center gap-4 p-4 rounded-xl hover:bg-indigo-800 transition-colors">
                        <i className="fa-solid fa-calendar-check"></i> Citas
                    </a>
                    <a href="#" className="flex items-center gap-4 p-4 rounded-xl hover:bg-indigo-800 transition-colors">
                        <i className="fa-solid fa-gears"></i> Configuración
                    </a>
                </nav>
            </aside>

            {/* Main Content */}
            <main className="flex-1 flex flex-col overflow-hidden">
                <header className="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-10 shadow-sm">
                    <h2 className="text-xl font-bold text-gray-800">Panel de Control</h2>
                    <div className="flex items-center gap-4">
                        <div className="text-right">
                            <p className="text-sm font-black text-indigo-900">Dr. Javier González</p>
                            <p className="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Especialista</p>
                        </div>
                        <div className="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                            <i className="fa-solid fa-user-md"></i>
                        </div>
                    </div>
                </header>

                <div className="flex-1 overflow-y-auto p-10 space-y-10">
                    <div className="flex justify-between items-end">
                        <div className="space-y-1">
                            <h3 className="text-3xl font-black text-indigo-950">Directorio de Pacientes</h3>
                            <p className="text-gray-500 font-medium italic">Gestión rápida y segura de expedientes.</p>
                        </div>
                        <button 
                            onClick={() => setIsModalOpen(true)}
                            className="bg-indigo-600 text-white px-8 py-4 rounded-2xl font-black text-sm shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-3"
                        >
                            <i className="fa-solid fa-plus"></i> Nueva Consulta
                        </button>
                    </div>

                    {/* Search & Table */}
                    <div className="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
                        <div className="p-8 border-b border-gray-50 bg-gray-50/50">
                            <div className="relative max-w-md">
                                <i className="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input 
                                    type="text" 
                                    placeholder="Buscar paciente por nombre o cédula..." 
                                    className="w-full pl-12 pr-6 py-4 rounded-2xl border-none ring-1 ring-gray-200 focus:ring-2 focus:ring-indigo-500 transition-all text-sm"
                                    onChange={handleSearch}
                                />
                            </div>
                        </div>

                        <div className="overflow-x-auto">
                            <table className="w-full text-left">
                                <thead className="bg-gray-50/50 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    <tr>
                                        <th className="px-8 py-6">Paciente</th>
                                        <th className="px-8 py-6">Cédula</th>
                                        <th className="px-8 py-6">Última Visita</th>
                                        <th className="px-8 py-6">Estado</th>
                                        <th className="px-8 py-6">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-50">
                                    {filteredPatients.map(patient => (
                                        <tr key={patient.id} className="hover:bg-indigo-50/30 transition-colors group">
                                            <td className="px-8 py-6">
                                                <p className="font-bold text-indigo-950 group-hover:text-indigo-600 transition-colors">{patient.name}</p>
                                                <p className="text-xs text-gray-400">{patient.email}</p>
                                            </td>
                                            <td className="px-8 py-6 font-medium text-gray-600">{patient.id_number}</td>
                                            <td className="px-8 py-6 text-sm text-gray-500">{patient.last_visit || 'Pendiente'}</td>
                                            <td className="px-8 py-6 text-sm">
                                                <span className="px-3 py-1 rounded-full bg-green-100 text-green-700 text-[10px] font-black uppercase">Activo</span>
                                            </td>
                                            <td className="px-8 py-6">
                                                <button className="text-indigo-600 hover:text-indigo-800 font-bold text-sm">Expediente</button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            {/* Modal */}
            {isModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-6 bg-indigo-950/40 backdrop-blur-sm">
                    <div className="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl p-12 relative animate-fade-in-up">
                        <button 
                            onClick={() => setIsModalOpen(false)}
                            className="absolute top-8 right-8 text-gray-400 hover:text-gray-600"
                        >
                            <i className="fa-solid fa-times text-xl"></i>
                        </button>
                        
                        <div className="mb-10 text-center">
                            <h3 className="text-3xl font-black text-indigo-950 tracking-tighter">Registrar Consulta</h3>
                            <p className="text-gray-400 uppercase text-[10px] font-black tracking-widest mt-2">Nueva entrada clínica</p>
                        </div>

                        <form onSubmit={handleSubmitConsultation} className="space-y-6">
                            <div>
                                <label className="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Paciente *</label>
                                <select 
                                    className="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none ring-1 ring-gray-200 focus:ring-2 focus:ring-indigo-500 text-sm font-bold"
                                    required
                                    onChange={(e) => setFormData({...formData, patient_id: e.target.value})}
                                >
                                    <option value="">Selecciona un paciente</option>
                                    {patients.map(p => <option key={p.id} value={p.id}>{p.name}</option>)}
                                </select>
                            </div>
                            <div className="grid grid-cols-2 gap-6">
                                <div>
                                    <label className="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Diagnóstico</label>
                                    <textarea 
                                        rows="3"
                                        className="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none ring-1 ring-gray-200 focus:ring-2 focus:ring-indigo-500 text-sm resize-none"
                                        onChange={(e) => setFormData({...formData, diagnosis: e.target.value})}
                                    ></textarea>
                                </div>
                                <div>
                                    <label className="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Tratamiento</label>
                                    <textarea 
                                        rows="3"
                                        className="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none ring-1 ring-gray-200 focus:ring-2 focus:ring-indigo-500 text-sm resize-none"
                                        onChange={(e) => setFormData({...formData, treatment: e.target.value})}
                                    ></textarea>
                                </div>
                            </div>
                            <div>
                                <label className="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Notas Adicionales</label>
                                <textarea 
                                    rows="2"
                                    className="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none ring-1 ring-gray-200 focus:ring-2 focus:ring-indigo-500 text-sm resize-none"
                                    onChange={(e) => setFormData({...formData, notes: e.target.value})}
                                ></textarea>
                            </div>

                            <div className="pt-6">
                                <button 
                                    type="submit" 
                                    disabled={loading}
                                    className="w-full bg-indigo-600 text-white py-5 rounded-2xl font-black text-lg shadow-xl hover:bg-indigo-700 transition-all disabled:opacity-50"
                                >
                                    {loading ? 'Guardando...' : 'Finalizar Consulta'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
};

export default Dashboard;
