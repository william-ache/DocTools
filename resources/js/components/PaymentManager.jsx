import React, { useState, useMemo } from 'react';

const PaymentManager = ({ defaultExchangeRate = 36.50 }) => {
    const [payments, setPayments] = useState([]);
    const [exchangeRate, setExchangeRate] = useState(defaultExchangeRate);
    const [amount, setAmount] = useState('');
    const [currency, setCurrency] = useState('USD');
    const [method, setMethod] = useState('Efectivo');

    const handleAddPayment = (e) => {
        e.preventDefault();
        if (!amount || amount <= 0) return;

        const newPayment = {
            id: Date.now(),
            amount: parseFloat(amount),
            currency,
            method,
            convertedAmount: currency === 'USD' ? amount * exchangeRate : amount / exchangeRate,
            timestamp: new Date().toLocaleTimeString()
        };

        setPayments([newPayment, ...payments]);
        setAmount('');
    };

    const totals = useMemo(() => {
        return payments.reduce((acc, p) => {
            if (p.currency === 'USD') {
                acc.usd += p.amount;
                acc.bs += p.convertedAmount;
            } else {
                acc.bs += p.amount;
                acc.usd += p.convertedAmount;
            }
            return acc;
        }, { usd: 0, bs: 0 });
    }, [payments]);

    return (
        <div className="max-w-4xl mx-auto space-y-8 p-6">
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                {/* Exchange Rate Card */}
                <div className="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-xl space-y-3">
                    <p className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tasa de Cambio</p>
                    <div className="flex items-center gap-3">
                        <span className="text-2xl font-black text-indigo-900">$1 = </span>
                        <input 
                            type="number" 
                            step="0.01"
                            value={exchangeRate}
                            onChange={(e) => setExchangeRate(e.target.value)}
                            className="w-full bg-gray-50 border-none ring-1 ring-gray-100 rounded-xl px-4 py-2 font-black text-indigo-600 focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>
                </div>

                {/* Daily Flow USD */}
                <div className="bg-indigo-900 p-6 rounded-[2rem] shadow-xl text-white">
                    <p className="text-[10px] font-bold opacity-60 uppercase tracking-widest mb-1">Total del Día (USD)</p>
                    <p className="text-3xl font-black">${totals.usd.toLocaleString(undefined, { minimumFractionDigits: 2 })}</p>
                </div>

                {/* Daily Flow BS */}
                <div className="bg-green-600 p-6 rounded-[2rem] shadow-xl text-white">
                    <p className="text-[10px] font-bold opacity-60 uppercase tracking-widest mb-1">Total del Día (BS)</p>
                    <p className="text-3xl font-black">Bs {totals.bs.toLocaleString(undefined, { minimumFractionDigits: 2 })}</p>
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-10">
                {/* Form Section */}
                <div className="bg-white p-10 rounded-[2.5rem] border border-gray-100 shadow-2xl space-y-8">
                    <h3 className="text-xl font-black text-indigo-950 flex items-center gap-3">
                        <i className="fa-solid fa-cash-register text-indigo-600"></i> Registrar Cobro
                    </h3>

                    <form onSubmit={handleAddPayment} className="space-y-6">
                        <div className="grid grid-cols-2 gap-4">
                            <div>
                                <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Monto</label>
                                <input 
                                    type="number"
                                    required
                                    value={amount}
                                    onChange={(e) => setAmount(e.target.value)}
                                    className="w-full px-6 py-4 bg-gray-50 border-none ring-1 ring-gray-100 rounded-2xl font-black text-xl text-indigo-900 focus:ring-2 focus:ring-indigo-500"
                                    placeholder="0.00"
                                />
                            </div>
                            <div>
                                <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Moneda</label>
                                <select 
                                    value={currency}
                                    onChange={(e) => setCurrency(e.target.value)}
                                    className="w-full px-6 py-4 bg-gray-50 border-none ring-1 ring-gray-100 rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-500 appearance-none h-[60px]"
                                >
                                    <option value="USD">Dólares (USD)</option>
                                    <option value="BS">Bolívares (BS)</option>
                                </select>
                            </div>
                        </div>

                        {amount > 0 && (
                            <div className="bg-indigo-50 p-4 rounded-2xl border border-indigo-100 animate-pulse">
                                <p className="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Conversión Sugerida</p>
                                <p className="text-lg font-black text-indigo-900">
                                    {currency === 'USD' ? `Bs ${(amount * exchangeRate).toFixed(2)}` : `$ ${(amount / exchangeRate).toFixed(2)}`}
                                </p>
                            </div>
                        )}

                        <div>
                            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 ml-1">Método de Pago</label>
                            <div className="grid grid-cols-3 gap-3">
                                {['Efectivo', 'Zelle', 'Pago Móvil'].map(m => (
                                    <button
                                        key={m}
                                        type="button"
                                        onClick={() => setMethod(m)}
                                        className={`py-3 rounded-xl text-xs font-black uppercase tracking-tight transition-all border-2 ${
                                            method === m 
                                            ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg' 
                                            : 'bg-white border-gray-100 text-gray-400 hover:border-indigo-200'
                                        }`}
                                    >
                                        {m}
                                    </button>
                                ))}
                            </div>
                        </div>

                        <button 
                            type="submit" 
                            className="w-full py-5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black shadow-xl transition-all transform hover:scale-[1.02] active:scale-95"
                        >
                            CONFIRMAR COBRO
                        </button>
                    </form>
                </div>

                {/* History Section */}
                <div className="space-y-6">
                    <h3 className="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-3 ml-2">
                        <i className="fa-solid fa-clock-rotate-left"></i> Flujo Reciente del Día
                    </h3>
                    
                    <div className="space-y-4 max-h-[480px] overflow-y-auto pr-2 custom-scrollbar">
                        {payments.length === 0 ? (
                            <div className="bg-gray-100/50 border-2 border-dashed border-gray-200 p-10 rounded-[2rem] text-center">
                                <p className="text-sm font-bold text-gray-400 italic">No hay transacciones registradas hoy.</p>
                            </div>
                        ) : (
                            payments.map(payment => (
                                <div key={payment.id} className="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
                                    <div className="flex items-center gap-4">
                                        <div className="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-indigo-600">
                                            <i className={`fa-solid ${payment.method === 'Efectivo' ? 'fa-money-bill' : 'fa-mobile-screen'}`}></i>
                                        </div>
                                        <div>
                                            <p className="text-sm font-black text-indigo-950">{payment.method}</p>
                                            <p className="text-[10px] font-bold text-gray-400 uppercase">{payment.timestamp}</p>
                                        </div>
                                    </div>
                                    <div className="text-right">
                                        <p className="text-lg font-black text-indigo-900">
                                            {payment.currency === 'USD' ? `$${payment.amount}` : `Bs${payment.amount}`}
                                        </p>
                                        <p className="text-[10px] font-bold text-gray-400 uppercase">
                                            Eq. {payment.currency === 'USD' ? `Bs ${payment.convertedAmount.toFixed(2)}` : `$ ${payment.convertedAmount.toFixed(2)}`}
                                        </p>
                                    </div>
                                </div>
                            ))
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default PaymentManager;
