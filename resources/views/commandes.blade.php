@extends('layouts.app')

@section('title', 'Commandes - Traccar TF')

@section('content')

<div class="main-container">
    <!-- Sidebar -->
    <aside class="sidebar command-sidebar">
        <div class="sidebar-header">
            <h3 class="sidebar-title">
                <i class="fas fa-terminal me-2"></i>Commandes
            </h3>
        </div>
        
        <div class="sidebar-search">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="commandSearch" class="search-input" placeholder="Rechercher...">
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <a href="#" class="sidebar-item active" data-filter="all">
                <i class="fas fa-list"></i>
                <span>Toutes les commandes</span>
                <span class="badge" id="countAll">0</span>
            </a>
            <a href="#" class="sidebar-item" data-filter="saved">
                <i class="fas fa-save"></i>
                <span>Modèles sauvegardés</span>
                <span class="badge" id="countSaved">0</span>
            </a>
        </nav>

        <div class="sidebar-section">
            <h4 class="section-title">Types de commandes</h4>
            <div class="command-types-list" id="commandTypesList">
                <!-- Types will be loaded dynamically -->
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-card">
            <div class="card-header-custom">
                <h3><i class="fas fa-terminal me-2"></i>Gestion des Commandes</h3>
                <div class="header-actions">
                    <button class="btn btn-success" id="btnRefresh">
                        <i class="fas fa-sync-alt"></i>
                        Rafraîchir
                    </button>
                    <button class="btn btn-primary" id="btnAddCommand" data-bs-toggle="modal" data-bs-target="#saveCommandModal">
                        <i class="fas fa-plus"></i>
                        Nouvelle commande
                    </button>
                </div>
            </div>
            
            <!-- Quick Send Command Section -->
            <div class="quick-command-section">
                <h4 class="section-header">
                    <i class="fas fa-paper-plane me-2"></i>Envoyer une commande rapide
                </h4>
                <div class="quick-command-form">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Appareil</label>
                            <select class="form-select" id="quickDevice">
                                <option value="">Sélectionner un appareil...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Type de commande</label>
                            <select class="form-select" id="quickCommandType">
                                <option value="">Sélectionner un type...</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="quickDataGroup" style="display: none;">
                            <label class="form-label">Données</label>
                            <input type="text" class="form-control" id="quickCommandData" placeholder="Données...">
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary" id="btnQuickSend">
                            <i class="fas fa-paper-plane me-1"></i>Envoyer
                        </button>
                        <span class="quick-result ms-3" id="quickResult"></span>
                    </div>
                </div>
            </div>

            <!-- Saved Commands Table -->
            <div class="commands-section">
                <h4 class="section-header">
                    <i class="fas fa-save me-2"></i>Commandes Sauvegardées
                </h4>
                <div class="table-responsive">
                    <table class="table table-hover" id="commandsTable">
                        <thead>
                            <tr>
                                <th width="5%">
                                    <input type="checkbox" class="form-check-input" id="selectAllCommands">
                                </th>
                                <th width="20%">Description</th>
                                <th width="15%">Type</th>
                                <th width="20%">Données</th>
                                <th width="15%">Appareils liés</th>
                                <th width="10%">Canal</th>
                                <th width="15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="commandsBody">
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Chargement...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal: Sauvegarder/Modifier une commande -->
<div class="modal fade" id="saveCommandModal" tabindex="-1" aria-labelledby="saveCommandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="saveCommandModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Nouvelle commande sauvegardée
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="saveCommandForm">
                    <input type="hidden" id="commandId" value="0">
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="commandDescription" class="form-label fw-bold">
                                <i class="fas fa-tag me-1"></i>Description <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="commandDescription" placeholder="Ex: Arrêter moteur véhicule A" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="commandTypeSelect" class="form-label fw-bold">
                                <i class="fas fa-list me-1"></i>Type de commande <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="commandTypeSelect" required>
                                <option value="">Sélectionner un type...</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-broadcast-tower me-1"></i>Canal de transmission
                            </label>
                            <div class="d-flex gap-3 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="textChannel" id="channelGprs" value="false" checked>
                                    <label class="form-check-label" for="channelGprs">GPRS</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="textChannel" id="channelSms" value="true">
                                    <label class="form-check-label" for="channelSms">SMS</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3" id="commandDataRow" style="display: none;">
                        <div class="col-md-12">
                            <label for="commandDataInput" class="form-label fw-bold">
                                <i class="fas fa-code me-1"></i>Données de la commande
                            </label>
                            <textarea class="form-control" id="commandDataInput" rows="3" placeholder="Entrez la commande brute ou les paramètres..."></textarea>
                            <div class="form-text">Pour les commandes personnalisées, entrez la commande complète ici.</div>
                        </div>
                    </div>

                    <div class="alert alert-info" id="commandTypeDescription">
                        <i class="fas fa-info-circle me-1"></i>
                        Sélectionnez un type de commande pour voir sa description.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Annuler
                </button>
                <button type="button" class="btn btn-primary" id="btnSaveCommand">
                    <i class="fas fa-save me-1"></i>Sauvegarder
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Envoyer commande à un appareil -->
<div class="modal fade" id="sendCommandModal" tabindex="-1" aria-labelledby="sendCommandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="sendCommandModalLabel">
                    <i class="fas fa-paper-plane me-2"></i>Envoyer la commande
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="sendCommandId">
                <input type="hidden" id="sendCommandType">
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Commande</label>
                    <div class="form-control bg-light" id="sendCommandDescription">-</div>
                </div>
                
                <div class="mb-3">
                    <label for="sendDeviceSelect" class="form-label fw-bold">
                        <i class="fas fa-car me-1"></i>Appareil cible <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="sendDeviceSelect" required>
                        <option value="">Sélectionner un appareil...</option>
                    </select>
                </div>
                
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Cette commande sera envoyée immédiatement à l'appareil sélectionné.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Annuler
                </button>
                <button type="button" class="btn btn-success" id="btnConfirmSend">
                    <i class="fas fa-paper-plane me-1"></i>Envoyer
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* Commands Page Styles */
.command-sidebar {
    width: 280px;
    background: #fff;
    border-right: 1px solid #e5e7eb;
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
}

.sidebar-title {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
}

.sidebar-search {
    padding: 15px;
    border-bottom: 1px solid #e5e7eb;
}

.search-input-wrapper {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
}

.search-input {
    width: 100%;
    padding: 10px 12px 10px 38px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s;
}

.search-input:focus {
    outline: none;
    border-color: #3b82f6;
}

.sidebar-nav {
    padding: 15px;
    border-bottom: 1px solid #e5e7eb;
}

.sidebar-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 15px;
    color: #4b5563;
    text-decoration: none;
    border-radius: 8px;
    margin-bottom: 5px;
    transition: all 0.2s;
}

.sidebar-item:hover {
    background: #f3f4f6;
    color: #1f2937;
}

.sidebar-item.active {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: #fff;
}

.sidebar-item .badge {
    margin-left: auto;
    background: rgba(0,0,0,0.1);
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.sidebar-item.active .badge {
    background: rgba(255,255,255,0.2);
}

.sidebar-section {
    padding: 15px;
    flex: 1;
    overflow-y: auto;
}

.section-title {
    font-size: 12px;
    font-weight: 600;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 10px;
}

.command-types-list {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.command-type-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: #f9fafb;
    border-radius: 6px;
    font-size: 13px;
    color: #4b5563;
    cursor: pointer;
    transition: all 0.2s;
}

.command-type-item:hover {
    background: #e5e7eb;
}

.command-type-item i {
    width: 20px;
    text-align: center;
    color: #6b7280;
}

/* Main Content */
.content-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.card-header-custom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e5e7eb;
}

.card-header-custom h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
}

.header-actions {
    display: flex;
    gap: 10px;
}

/* Quick Command Section */
.quick-command-section {
    padding: 24px;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border-bottom: 1px solid #bfdbfe;
}

.section-header {
    margin: 0 0 15px 0;
    font-size: 16px;
    font-weight: 600;
    color: #1e40af;
}

.quick-command-form {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(59, 130, 246, 0.1);
}

.quick-result {
    font-weight: 600;
    font-size: 14px;
}

.quick-result.success {
    color: #10b981;
}

.quick-result.error {
    color: #ef4444;
}

/* Commands Section */
.commands-section {
    padding: 24px;
}

.commands-section .section-header {
    color: #374151;
    margin-bottom: 20px;
}

/* Table Styles */
.table {
    margin: 0;
}

.table thead th {
    background: #f9fafb;
    border-bottom: 2px solid #e5e7eb;
    font-weight: 600;
    font-size: 13px;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 14px 12px;
}

.table tbody td {
    padding: 14px 12px;
    vertical-align: middle;
    border-bottom: 1px solid #f3f4f6;
}

.table tbody tr:hover {
    background: #f9fafb;
}

.command-type-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: #fff;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.channel-badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.channel-badge.gprs {
    background: #dbeafe;
    color: #1e40af;
}

.channel-badge.sms {
    background: #d1fae5;
    color: #065f46;
}

.device-count-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    background: #f3f4f6;
    border-radius: 12px;
    font-size: 12px;
    color: #4b5563;
}

.action-btns {
    display: flex;
    gap: 6px;
}

.btn-action {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 14px;
}

.btn-action.send {
    background: #d1fae5;
    color: #059669;
}

.btn-action.send:hover {
    background: #10b981;
    color: #fff;
}

.btn-action.edit {
    background: #dbeafe;
    color: #2563eb;
}

.btn-action.edit:hover {
    background: #3b82f6;
    color: #fff;
}

.btn-action.delete {
    background: #fee2e2;
    color: #dc2626;
}

.btn-action.delete:hover {
    background: #ef4444;
    color: #fff;
}

/* Modal Styles */
#saveCommandModal .modal-content,
#sendCommandModal .modal-content {
    border: none;
    border-radius: 12px;
}

#saveCommandModal .modal-header,
#sendCommandModal .modal-header {
    border-bottom: none;
    border-radius: 12px 12px 0 0;
}

#saveCommandModal .modal-body,
#sendCommandModal .modal-body {
    padding: 24px;
}

#saveCommandModal .form-control,
#saveCommandModal .form-select,
#sendCommandModal .form-select {
    border-radius: 8px;
    border: 2px solid #e5e7eb;
    padding: 10px 14px;
}

#saveCommandModal .form-control:focus,
#saveCommandModal .form-select:focus,
#sendCommandModal .form-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #9ca3af;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.5;
}

.empty-state p {
    margin: 0;
    font-size: 15px;
}

/* Responsive */
@media (max-width: 991px) {
    .command-sidebar {
        display: none;
    }
    
    .card-header-custom {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .quick-command-form .row {
        gap: 15px;
    }
}

@media (max-width: 768px) {
    .quick-command-section {
        padding: 15px;
    }
    
    .commands-section {
        padding: 15px;
    }
    
    .table thead th {
        font-size: 11px;
        padding: 10px 8px;
    }
    
    .table tbody td {
        font-size: 13px;
        padding: 10px 8px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Commands page loaded');
    
    let allCommands = [];
    let allDevices = [];
    let commandTypes = [];
    
    // Command type descriptions
    const commandDescriptions = {
        'custom': 'Envoyer une commande personnalisée brute à l\'appareil.',
        'positionPeriodic': 'Définir l\'intervalle de mise à jour de position.',
        'positionStop': 'Arrêter les mises à jour de position.',
        'engineStop': 'Couper le moteur du véhicule à distance.',
        'engineResume': 'Réactiver le moteur du véhicule.',
        'alarmArm': 'Activer l\'alarme du véhicule.',
        'alarmDisarm': 'Désactiver l\'alarme du véhicule.',
        'setTimezone': 'Configurer le fuseau horaire de l\'appareil.',
        'requestPhoto': 'Demander une photo depuis la caméra de l\'appareil.',
        'rebootDevice': 'Redémarrer l\'appareil GPS.',
        'sendSms': 'Envoyer un SMS via l\'appareil.',
        'sendUssd': 'Envoyer une commande USSD.',
        'sosNumber': 'Configurer le numéro SOS.',
        'silenceTime': 'Définir une période de silence.',
        'outputControl': 'Contrôler les sorties de l\'appareil.',
        'configuration': 'Envoyer une configuration à l\'appareil.',
        'getVersion': 'Obtenir la version du firmware.',
        'setConnection': 'Configurer la connexion serveur.',
        'setOdometer': 'Réinitialiser/configurer l\'odomètre.',
        'getDeviceStatus': 'Obtenir le statut de l\'appareil.',
        'setSpeedLimit': 'Définir la limite de vitesse.',
        'modePowerSaving': 'Activer le mode économie d\'énergie.',
        'modeDeepSleep': 'Activer le mode veille profonde.',
        'movementAlarm': 'Configurer l\'alarme de mouvement.'
    };
    
    // Format command type for display
    function formatCommandType(type) {
        const formats = {
            'custom': 'Personnalisée',
            'positionPeriodic': 'Position périodique',
            'positionStop': 'Arrêter position',
            'engineStop': 'Arrêter moteur',
            'engineResume': 'Redémarrer moteur',
            'alarmArm': 'Activer alarme',
            'alarmDisarm': 'Désactiver alarme',
            'setTimezone': 'Fuseau horaire',
            'requestPhoto': 'Photo',
            'rebootDevice': 'Redémarrer',
            'sendSms': 'SMS',
            'sendUssd': 'USSD',
            'sosNumber': 'Numéro SOS',
            'outputControl': 'Contrôle sorties',
            'configuration': 'Configuration',
            'getVersion': 'Version',
            'setConnection': 'Connexion',
            'setOdometer': 'Odomètre',
            'getDeviceStatus': 'Statut',
            'setSpeedLimit': 'Limite vitesse',
            'modePowerSaving': 'Économie énergie',
            'modeDeepSleep': 'Veille profonde',
            'movementAlarm': 'Alarme mouvement'
        };
        return formats[type] || type;
    }
    
    // Get command icon
    function getCommandIcon(type) {
        const icons = {
            'custom': 'fa-code',
            'positionPeriodic': 'fa-clock',
            'positionStop': 'fa-stop',
            'engineStop': 'fa-power-off',
            'engineResume': 'fa-play',
            'alarmArm': 'fa-shield-alt',
            'alarmDisarm': 'fa-shield',
            'requestPhoto': 'fa-camera',
            'rebootDevice': 'fa-redo',
            'sendSms': 'fa-sms',
            'outputControl': 'fa-toggle-on',
            'configuration': 'fa-cog',
            'setOdometer': 'fa-tachometer-alt',
            'setSpeedLimit': 'fa-tachometer-alt'
        };
        return icons[type] || 'fa-terminal';
    }
    
    // Initialize
    loadDevices();
    loadCommands();
    loadCommandTypes();
    setupEventListeners();
    
    // Load devices
    async function loadDevices() {
        try {
            const response = await fetch('/api/traccar/devices?all=true');
            const data = await response.json();
            
            if (data.success) {
                allDevices = data.devices || [];
                populateDeviceSelects();
            }
        } catch (error) {
            console.error('Error loading devices:', error);
        }
    }
    
    // Populate device selects
    function populateDeviceSelects() {
        const selects = ['quickDevice', 'sendDeviceSelect'];
        
        selects.forEach(id => {
            const select = document.getElementById(id);
            if (!select) return;
            
            select.innerHTML = '<option value="">Sélectionner un appareil...</option>';
            allDevices.forEach(device => {
                const option = document.createElement('option');
                option.value = device.id;
                option.textContent = `${device.name} (${device.uniqueId})`;
                select.appendChild(option);
            });
        });
    }
    
    // Load commands
    async function loadCommands() {
        try {
            const response = await fetch('/api/traccar/commands?all=true');
            const data = await response.json();
            
            if (data.success) {
                allCommands = data.commands || [];
                renderCommands();
                updateCounts();
            } else {
                document.getElementById('commandsBody').innerHTML = `
                    <tr>
                        <td colspan="7" class="empty-state">
                            <i class="fas fa-terminal"></i>
                            <p>Aucune commande sauvegardée</p>
                        </td>
                    </tr>
                `;
            }
        } catch (error) {
            console.error('Error loading commands:', error);
            document.getElementById('commandsBody').innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-danger py-4">
                        <i class="fas fa-exclamation-circle me-2"></i>Erreur de chargement
                    </td>
                </tr>
            `;
        }
    }
    
    // Load command types
    async function loadCommandTypes() {
        // Common command types
        commandTypes = [
            { type: 'custom' },
            { type: 'positionPeriodic' },
            { type: 'positionStop' },
            { type: 'engineStop' },
            { type: 'engineResume' },
            { type: 'alarmArm' },
            { type: 'alarmDisarm' },
            { type: 'setTimezone' },
            { type: 'requestPhoto' },
            { type: 'rebootDevice' },
            { type: 'sendSms' },
            { type: 'outputControl' },
            { type: 'configuration' },
            { type: 'setOdometer' },
            { type: 'getDeviceStatus' },
            { type: 'setSpeedLimit' }
        ];
        
        populateCommandTypeSelects();
        renderCommandTypesList();
    }
    
    // Populate command type selects
    function populateCommandTypeSelects() {
        const selects = ['quickCommandType', 'commandTypeSelect'];
        
        selects.forEach(id => {
            const select = document.getElementById(id);
            if (!select) return;
            
            select.innerHTML = '<option value="">Sélectionner un type...</option>';
            commandTypes.forEach(ct => {
                const option = document.createElement('option');
                option.value = ct.type;
                option.textContent = formatCommandType(ct.type);
                select.appendChild(option);
            });
        });
    }
    
    // Render command types list in sidebar
    function renderCommandTypesList() {
        const container = document.getElementById('commandTypesList');
        if (!container) return;
        
        container.innerHTML = commandTypes.map(ct => `
            <div class="command-type-item" data-type="${ct.type}">
                <i class="fas ${getCommandIcon(ct.type)}"></i>
                <span>${formatCommandType(ct.type)}</span>
            </div>
        `).join('');
    }
    
    // Render commands table
    function renderCommands() {
        const tbody = document.getElementById('commandsBody');
        
        if (allCommands.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="empty-state">
                        <i class="fas fa-terminal"></i>
                        <p>Aucune commande sauvegardée</p>
                    </td>
                </tr>
            `;
            return;
        }
        
        tbody.innerHTML = allCommands.map(cmd => `
            <tr data-id="${cmd.id}">
                <td>
                    <input type="checkbox" class="form-check-input command-checkbox" data-id="${cmd.id}">
                </td>
                <td>
                    <strong>${cmd.description || '-'}</strong>
                </td>
                <td>
                    <span class="command-type-badge">
                        <i class="fas ${getCommandIcon(cmd.type)}"></i>
                        ${formatCommandType(cmd.type)}
                    </span>
                </td>
                <td>
                    <code class="text-muted">${cmd.attributes?.data || '-'}</code>
                </td>
                <td>
                    <span class="device-count-badge">
                        <i class="fas fa-link"></i>
                        ${cmd.deviceId ? '1 appareil' : 'Tous'}
                    </span>
                </td>
                <td>
                    <span class="channel-badge ${cmd.textChannel ? 'sms' : 'gprs'}">
                        ${cmd.textChannel ? 'SMS' : 'GPRS'}
                    </span>
                </td>
                <td>
                    <div class="action-btns">
                        <button class="btn-action send" onclick="openSendModal(${cmd.id})" title="Envoyer">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                        <button class="btn-action edit" onclick="editCommand(${cmd.id})" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-action delete" onclick="deleteCommand(${cmd.id})" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }
    
    // Update counts
    function updateCounts() {
        document.getElementById('countAll').textContent = allCommands.length;
        document.getElementById('countSaved').textContent = allCommands.length;
    }
    
    // Setup event listeners
    function setupEventListeners() {
        // Refresh button
        document.getElementById('btnRefresh').addEventListener('click', function() {
            this.querySelector('i').classList.add('fa-spin');
            Promise.all([loadCommands(), loadDevices()]).then(() => {
                setTimeout(() => {
                    this.querySelector('i').classList.remove('fa-spin');
                }, 500);
            });
        });
        
        // Quick command type change
        document.getElementById('quickCommandType').addEventListener('change', function() {
            const dataGroup = document.getElementById('quickDataGroup');
            dataGroup.style.display = this.value === 'custom' ? 'block' : 'none';
        });
        
        // Modal command type change
        document.getElementById('commandTypeSelect').addEventListener('change', function() {
            const type = this.value;
            const dataRow = document.getElementById('commandDataRow');
            const description = document.getElementById('commandTypeDescription');
            
            dataRow.style.display = type === 'custom' ? 'block' : 'none';
            
            if (type && commandDescriptions[type]) {
                description.innerHTML = '<i class="fas fa-info-circle me-1"></i> ' + commandDescriptions[type];
                description.className = 'alert alert-info';
            } else {
                description.innerHTML = '<i class="fas fa-info-circle me-1"></i> Sélectionnez un type de commande.';
            }
        });
        
        // Quick send button
        document.getElementById('btnQuickSend').addEventListener('click', quickSendCommand);
        
        // Save command button
        document.getElementById('btnSaveCommand').addEventListener('click', saveCommand);
        
        // Confirm send button
        document.getElementById('btnConfirmSend').addEventListener('click', confirmSendCommand);
        
        // Reset modal on close
        document.getElementById('saveCommandModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('commandId').value = '0';
            document.getElementById('saveCommandForm').reset();
            document.getElementById('commandDataRow').style.display = 'none';
            document.getElementById('saveCommandModalLabel').innerHTML = '<i class="fas fa-plus-circle me-2"></i>Nouvelle commande sauvegardée';
        });
        
        // Search
        document.getElementById('commandSearch').addEventListener('input', function() {
            const search = this.value.toLowerCase();
            const rows = document.querySelectorAll('#commandsBody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(search) ? '' : 'none';
            });
        });
    }
    
    // Quick send command
    async function quickSendCommand() {
        const deviceId = document.getElementById('quickDevice').value;
        const type = document.getElementById('quickCommandType').value;
        const data = document.getElementById('quickCommandData').value;
        const resultEl = document.getElementById('quickResult');
        
        if (!deviceId || !type) {
            resultEl.textContent = 'Sélectionnez un appareil et un type de commande.';
            resultEl.className = 'quick-result ms-3 error';
            return;
        }
        
        const btn = document.getElementById('btnQuickSend');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Envoi...';
        
        try {
            const commandData = {
                deviceId: parseInt(deviceId),
                type: type
            };
            
            if (type === 'custom' && data) {
                commandData.data = data;
            }
            
            const response = await fetch('/api/traccar/commands/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(commandData)
            });
            
            const result = await response.json();
            
            if (result.success) {
                resultEl.textContent = '✓ Commande envoyée avec succès!';
                resultEl.className = 'quick-result ms-3 success';
            } else {
                resultEl.textContent = '✗ Erreur: ' + (result.message || 'Échec');
                resultEl.className = 'quick-result ms-3 error';
            }
        } catch (error) {
            resultEl.textContent = '✗ Erreur de connexion';
            resultEl.className = 'quick-result ms-3 error';
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Envoyer';
        }
    }
    
    // Save command
    async function saveCommand() {
        const id = document.getElementById('commandId').value;
        const description = document.getElementById('commandDescription').value;
        const type = document.getElementById('commandTypeSelect').value;
        const textChannel = document.querySelector('input[name="textChannel"]:checked').value === 'true';
        const data = document.getElementById('commandDataInput').value;
        
        if (!description || !type) {
            showWarning('Veuillez remplir la description et le type de commande.');
            return;
        }
        
        const btn = document.getElementById('btnSaveCommand');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Sauvegarde...';
        
        try {
            const commandData = {
                description: description,
                type: type,
                textChannel: textChannel
            };
            
            if (type === 'custom' && data) {
                commandData.attributes = { data: data };
            }
            
            const isEdit = id && id !== '0';
            const url = isEdit ? `/api/traccar/commands/${id}` : '/api/traccar/commands';
            const method = isEdit ? 'PUT' : 'POST';
            
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(commandData)
            });
            
            const result = await response.json();
            
            if (result.success) {
                bootstrap.Modal.getInstance(document.getElementById('saveCommandModal')).hide();
                loadCommands();
                showToast('Commande sauvegardée avec succès', 'success');
            } else {
                showError('Erreur: ' + (result.message || 'Échec de la sauvegarde'));
            }
        } catch (error) {
            showError('Erreur de connexion au serveur.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save me-1"></i>Sauvegarder';
        }
    }
    
    // Edit command
    window.editCommand = function(id) {
        const command = allCommands.find(c => c.id === id);
        if (!command) return;
        
        document.getElementById('commandId').value = id;
        document.getElementById('commandDescription').value = command.description || '';
        document.getElementById('commandTypeSelect').value = command.type || '';
        document.getElementById('commandDataInput').value = command.attributes?.data || '';
        
        if (command.textChannel) {
            document.getElementById('channelSms').checked = true;
        } else {
            document.getElementById('channelGprs').checked = true;
        }
        
        document.getElementById('commandDataRow').style.display = command.type === 'custom' ? 'block' : 'none';
        document.getElementById('saveCommandModalLabel').innerHTML = '<i class="fas fa-edit me-2"></i>Modifier la commande';
        
        new bootstrap.Modal(document.getElementById('saveCommandModal')).show();
    };
    
    // Delete command
    window.deleteCommand = async function(id) {
        const confirmed = await showDeleteConfirm('cette commande');
        if (!confirmed) return;
        
        try {
            const response = await fetch(`/api/traccar/commands/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                loadCommands();
                showToast('Commande supprimée avec succès', 'success');
            } else {
                showError('Erreur: ' + (result.message || 'Échec de la suppression'));
            }
        } catch (error) {
            showError('Erreur de connexion au serveur.');
        }
    };
    
    // Open send modal
    window.openSendModal = function(id) {
        const command = allCommands.find(c => c.id === id);
        if (!command) return;
        
        document.getElementById('sendCommandId').value = id;
        document.getElementById('sendCommandType').value = command.type;
        document.getElementById('sendCommandDescription').textContent = `${command.description} (${formatCommandType(command.type)})`;
        
        new bootstrap.Modal(document.getElementById('sendCommandModal')).show();
    };
    
    // Confirm send command
    async function confirmSendCommand() {
        const commandId = document.getElementById('sendCommandId').value;
        const commandType = document.getElementById('sendCommandType').value;
        const deviceId = document.getElementById('sendDeviceSelect').value;
        
        if (!deviceId) {
            showWarning('Veuillez sélectionner un appareil.');
            return;
        }
        
        const command = allCommands.find(c => c.id === parseInt(commandId));
        
        const btn = document.getElementById('btnConfirmSend');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Envoi...';
        
        try {
            const commandData = {
                deviceId: parseInt(deviceId),
                type: commandType
            };
            
            if (command && command.attributes?.data) {
                commandData.data = command.attributes.data;
            }
            
            const response = await fetch('/api/traccar/commands/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(commandData)
            });
            
            const result = await response.json();
            
            if (result.success) {
                bootstrap.Modal.getInstance(document.getElementById('sendCommandModal')).hide();
                showSuccess('Commande envoyée avec succès!');
            } else {
                showError('Erreur: ' + (result.message || 'Échec de l\'envoi'));
            }
        } catch (error) {
            showError('Erreur de connexion au serveur.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Envoyer';
        }
    }
});
</script>
@endpush
