// ====================
// Configuration
// ====================
const CONFIG = {
    ANIMATION_DELAY: 800,
    ERROR_DISPLAY_DURATION: 3000,
    DEFAULT_COVER_PATH: '../images/default-cover.jpg',
    STATUS_LABELS: {
        'to_read': 'À lire',
        'reading': 'En cours',
        'finished': 'Terminé',
        
    }
};

// ====================
// Gestionnaire de notifications
// ====================
const NotificationManager = {
    show(message, type = 'error') {
        const notification = document.createElement('div');
        notification.classList.add('notification', `notification-${type}`);
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => notification.remove(), CONFIG.ERROR_DISPLAY_DURATION);
    },

    showError(message) {
        this.show(message, 'error');
    },

    showSuccess(message) {
        this.show(message, 'success');
    }
};

// ====================
// Validation
// ====================
class BookValidator {
    constructor() {
        this.errors = [];
    }

    validateBook(bookData) {
        this.errors = [];
        
        this.validateField('titre', bookData.titre, 2);
        this.validateField('auteur', bookData.auteur, 2);
        this.validateNumber('nombre_pages', bookData.nombre_pages);
        this.validateDate(bookData.date);
        this.validateField('statut', bookData.statut, 2);
        this.validateGenres(bookData.genres);

        return this.errors.length === 0;
    }

    validateField(fieldName, value, minLength) {
        if (!value || value.trim().length < minLength) {
            this.errors.push(`Le champ ${fieldName} doit contenir au moins ${minLength} caractères`);
        }
    }

    validateNumber(fieldName, value) {
        if (!value || value < 1) {
            this.errors.push(`Le champ ${fieldName} doit être supérieur à 0`);
        }
    }

    validateDate(dateString) {
        const date = new Date(dateString);
        if (!dateString || isNaN(date.getTime())) {
            this.errors.push('La date n\'est pas valide');
        }
    }

    validateGenres(genres) {
        if (!genres || !Array.isArray(genres) || genres.length === 0) {
            this.errors.push('Sélectionnez au moins un genre');
        }
    }

    getErrors() {
        return this.errors;
    }
}

// ====================
// Navigation
// ====================
const Navigation = {
    initialize() {
        const navLinks = document.querySelectorAll('a[href]:not([href^="#"])');
        navLinks.forEach(link => {
            link.addEventListener('click', this.showLoadingPage);
        });

        const returnButton = document.querySelector('.return-button');
        if (returnButton) {
            returnButton.addEventListener('click', this.handleReturn);
        }
    },

    showLoadingPage(event) {
        event.preventDefault();
        const destination = this.getAttribute('href');

        const loadingPage = Navigation.createLoadingPage();
        document.body.appendChild(loadingPage);

        setTimeout(() => {
            window.location.href = destination;
        }, CONFIG.ANIMATION_DELAY);
    },

    createLoadingPage() {
        const loadingPage = document.createElement('div');
        loadingPage.classList.add('loading-page');
        loadingPage.innerHTML = `
            <div class="loading-content">
                <h1><span class="logo-blue">Blue</span><span class="logo-reading">Reading</span></h1>
                <div class="loader"></div>
            </div>
        `;
        return loadingPage;
    },

    handleReturn(event) {
        event.preventDefault();
        window.location.href = document.referrer || '../index.html';
    }
};

// ====================
// Gestionnaire de formulaire
// ====================
const FormManager = {
    initialize() {
        const bookForm = document.getElementById('bookForm');
        if (bookForm) {
            bookForm.addEventListener('submit', (e) => this.handleSubmission(e));
        }
    },

    async handleSubmission(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        const bookData = this.createBookDataObject(formData);

        const validator = new BookValidator();
        if (!validator.validateBook(bookData)) {
            validator.getErrors().forEach(error => NotificationManager.showError(error));
            return;
        }

        try {
            const response = await this.sendBookToServer(bookData);
            if (response.success) {
                NotificationManager.showSuccess('Livre ajouté avec succès !');
                this.resetForm(event.target);
                BooksManager.refreshBooksList();
            } else {
                NotificationManager.showError(response.error || 'Erreur lors de l\'ajout du livre');
            }
        } catch (error) {
            NotificationManager.showError('Une erreur est survenue lors de l\'ajout du livre');
            console.error('Erreur de soumission:', error);
        }
    },

    createBookDataObject(formData) {
        return {
            titre: formData.get('titre'),
            auteur: formData.get('auteur'),
            nombre_pages: parseInt(formData.get('pages')),
            genres: formData.getAll('genres[]'),
            date: formData.get('date'),
            statut: formData.get('statut'),
            couverture: formData.get('coverImage'),
            fichier_book: formData.get('bookFile'),
            etiquettes: formData.get('etiquettes')?.split(',').map(tag => tag.trim()) || []
        };
    },

    async sendBookToServer(bookData) {
        const formData = new FormData();
        Object.entries(bookData).forEach(([key, value]) => {
            if (Array.isArray(value)) {
                value.forEach(item => formData.append(`${key}[]`, item));
            } else {
                formData.append(key, value);
            }
        });

        const response = await fetch('views/livres/traitement-ajout.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error(`Erreur serveur: ${response.status}`);
        }

        return await response.json();
    },

    resetForm(form) {
        form.reset();
        form.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
    },

    toggleFormVisibility(showFileUpload) {
        document.getElementById('fileUploadForm')?.classList.toggle('d-none', !showFileUpload);
        document.getElementById('firstStep')?.classList.toggle('d-none', showFileUpload);
    }
};

// ====================
// Gestionnaire de livres
// ====================
const BooksManager = {
    createBookElement(book) {
        // Création du conteneur principal
        const bookDiv = document.createElement('div');
        bookDiv.className = 'col-12 col-md-6 col-lg-4 book-item';
        
        // Création de la structure HTML
        bookDiv.innerHTML = `
            <div class="card h-100 book-card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="book-cover-wrapper me-3">
                            ${book.couverture ? 
                                `<img src="${this.escapeHtml(book.couverture)}" 
                                    alt="Couverture de ${this.escapeHtml(book.titre)}" 
                                    class="book-cover">` 
                                : ''}
                        </div>
                        <div class="book-info flex-grow-1">
                            <h5 class="book-title fw-bold mb-2">
                                ${this.escapeHtml(book.titre)}
                            </h5>
                            <p class="book-author text-muted mb-2">
                                ${this.escapeHtml(book.auteur)}
                            </p>
                            <div class="book-tags mb-3">
                                <span class="badge bg-primary rounded-pill">
                                    ${this.escapeHtml(book.genre)}
                                </span>
                                <span class="badge bg-success rounded-pill">
                                    ${this.escapeHtml(book.statut_lecture || 'Non défini')}
                                </span>
                            </div>
                            <div class="book-actions">
                                <a href="index.php?action=modifierLivre&id=${book.id}" 
                                    class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-edit me-1"></i>Modifier
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger delete-book" 
                                        data-book-id="${book.id}">
                                    <i class="fas fa-trash-alt me-1"></i>Supprimer
                                </button>
                                <a href="index.php?action=lireLivre&id=${book.id}" 
                                    class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Ajout du gestionnaire d'événement pour le bouton supprimer
        const deleteButton = bookDiv.querySelector('.delete-book');
        deleteButton.addEventListener('click', () => {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce livre ?')) {
                this.deleteBook(book.id);
            }
        });

        return bookDiv;
    },

    // Fonction utilitaire pour échapper les caractères HTML
    escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    },

    // Fonction de suppression
    async deleteBook(id) {
        try {
            const response = await fetch('index.php?action=supprimerLivre', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${id}`
            });

            if (response.ok) {
                // Rafraîchir la liste des livres
                this.refreshBooksList();
                NotificationManager.showSuccess('Livre supprimé avec succès');
            } else {
                throw new Error('Erreur lors de la suppression');
            }
        } catch (error) {
            NotificationManager.showError('Erreur lors de la suppression du livre');
            console.error('Erreur:', error);
        }
    }
};

// Gestionnaire de filtres et recherche
const FilterManager = {
    initialize() {
        // Récupération des éléments DOM
        this.statusFilter = document.getElementById('filterStatus');
        this.genreFilter = document.getElementById('filterGenre');
        this.searchInput = document.getElementById('searchInput');
        this.searchButton = this.searchInput?.nextElementSibling;

        // Ajout des écouteurs d'événements
        this.setupEventListeners();

        // État initial des filtres
        this.currentFilters = {
            status: 'all',
            genre: 'all',
            searchTerm: ''
        };
    },

    setupEventListeners() {
        // Événements pour les filtres select
        if (this.statusFilter) {
            this.statusFilter.addEventListener('change', () => this.handleFiltersChange());
        }
        if (this.genreFilter) {
            this.genreFilter.addEventListener('change', () => this.handleFiltersChange());
        }

        // Événements pour la recherche
        if (this.searchInput) {
            // Recherche en temps réel lors de la saisie
            this.searchInput.addEventListener('input', () => {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => this.handleFiltersChange(), 300);
            });

            // Recherche lors de l'appui sur Entrée
            this.searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.handleFiltersChange();
                }
            });
        }

        // Événement pour le bouton de recherche
        if (this.searchButton) {
            this.searchButton.addEventListener('click', () => this.handleFiltersChange());
        }
    },

    handleFiltersChange() {
        // Mise à jour de l'état des filtres
        this.currentFilters = {
            status: this.statusFilter?.value || 'all',
            genre: this.genreFilter?.value || 'all',
            searchTerm: this.searchInput?.value.toLowerCase().trim() || ''
        };

        // Appliquer les filtres
        this.applyFilters();
    },

    applyFilters() {
        // Récupérer tous les éléments de livre
        const bookItems = document.querySelectorAll('.book-item');

        bookItems.forEach(bookItem => {
            const matchesStatus = this.matchesStatusFilter(bookItem);
            const matchesGenre = this.matchesGenreFilter(bookItem);
            const matchesSearch = this.matchesSearchTerm(bookItem);

            // Afficher ou masquer l'élément selon les filtres
            if (matchesStatus && matchesGenre && matchesSearch) {
                bookItem.style.display = '';
            } else {
                bookItem.style.display = 'none';
            }
        });

        // Mettre à jour l'affichage "Aucun livre trouvé"
        this.updateNoResultsMessage(bookItems);
    },

    matchesStatusFilter(bookItem) {
        if (this.currentFilters.status === 'all') return true;

        const statusBadge = bookItem.querySelector('.badge.bg-success');
        if (!statusBadge) return false;

        const status = statusBadge.textContent.toLowerCase();
        const filterMapping = {
            'reading': 'en cours',
            'finished': 'terminé',
            'toRead': 'à lire'
        };

        return status.includes(filterMapping[this.currentFilters.status] || '');
    },

    matchesGenreFilter(bookItem) {
        if (this.currentFilters.genre === 'all') return true;

        const genreBadge = bookItem.querySelector('.badge.bg-primary');
        if (!genreBadge) return false;

        return genreBadge.textContent.toLowerCase().includes(this.currentFilters.genre.toLowerCase());
    },

    matchesSearchTerm(bookItem) {
        if (!this.currentFilters.searchTerm) return true;

        const searchTerm = this.currentFilters.searchTerm.toLowerCase();
        const title = bookItem.querySelector('.book-title')?.textContent.toLowerCase() || '';
        const author = bookItem.querySelector('.book-author')?.textContent.toLowerCase() || '';

        return title.includes(searchTerm) || author.includes(searchTerm);
    },

    updateNoResultsMessage(bookItems) {
        // Supprimer l'ancien message s'il existe
        const existingMessage = document.querySelector('.no-results-message');
        if (existingMessage) {
            existingMessage.remove();
        }

        // Vérifier si des livres sont visibles
        const visibleBooks = Array.from(bookItems).some(book => book.style.display !== 'none');

        if (!visibleBooks) {
            const container = document.getElementById('booksContainer');
            const message = document.createElement('div');
            message.className = 'col-12 text-center no-results-message';
            message.innerHTML = `
                <div class="alert alert-info">
                    Aucun livre ne correspond à vos critères de recherche.
                </div>
            `;
            container.appendChild(message);
        }
    }
};


// ====================
// Initialisation
// ====================
document.addEventListener('DOMContentLoaded', () => {
    Navigation.initialize();
    FormManager.initialize();
    BooksManager.initialize();
    FilterManager.initialize();
});