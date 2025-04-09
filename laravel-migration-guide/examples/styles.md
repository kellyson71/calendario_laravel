# Exemplos de Estilos CSS

## Estilos Base

### tailwind.config.js
```javascript
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                'primary': {
                    '50': '#f0f9ff',
                    '100': '#e0f2fe',
                    '200': '#bae6fd',
                    '300': '#7dd3fc',
                    '400': '#38bdf8',
                    '500': '#0ea5e9',
                    '600': '#0284c7',
                    '700': '#0369a1',
                    '800': '#075985',
                    '900': '#0c4a6e',
                },
            },
            fontFamily: {
                sans: ['Inter var', 'sans-serif'],
            },
        },
    },
    plugins: [],
}
```

### resources/css/app.css
```css
@import 'tailwindcss/base';
@import 'tailwindcss/components';
@import 'tailwindcss/utilities';
@import '@fortawesome/fontawesome-free/css/all.css';

/* Estilos personalizados */
.bg-gradient-custom {
    background: linear-gradient(135deg, #05a0ef, #0444ab);
}

.calendar-day {
    @apply p-2 border h-24 relative;
}

.calendar-day.today {
    @apply bg-blue-50;
}

.calendar-day .day-number {
    @apply absolute top-0 right-0 p-1 text-sm text-gray-600;
}

.calendar-day.today .day-number {
    @apply text-blue-600 font-bold;
}

.appointment-item {
    @apply text-xs p-1 mb-1 bg-blue-100 rounded;
}

.sidebar-item {
    @apply p-3 border-b hover:bg-gray-50 cursor-pointer;
}

.sidebar-item .patient-name {
    @apply font-medium;
}

.sidebar-item .time {
    @apply text-sm text-gray-500;
}

.sidebar-item .doctor {
    @apply text-sm text-gray-600;
}

/* Estilos do modal */
.modal-backdrop {
    @apply fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center;
}

.modal-content {
    @apply bg-white rounded-lg shadow-xl max-w-lg w-full mx-4;
}

.modal-header {
    @apply p-4 border-b flex justify-between items-center;
}

.modal-body {
    @apply p-4;
}

.modal-footer {
    @apply p-4 border-t flex justify-end space-x-2;
}

/* Estilos do formulário */
.form-group {
    @apply mb-5;
}

.form-label {
    @apply block text-gray-700 text-sm font-medium mb-2;
}

.form-input {
    @apply appearance-none border border-gray-300 rounded-md w-full py-2.5 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500;
}

.form-input.error {
    @apply border-red-500;
}

.form-error {
    @apply text-red-600 text-xs mt-1;
}

/* Estilos do botão */
.btn {
    @apply font-medium py-2.5 px-4 rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-opacity-50;
}

.btn-primary {
    @apply bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500;
}

.btn-secondary {
    @apply bg-gray-200 hover:bg-gray-300 text-gray-800 focus:ring-gray-500;
}

.btn-danger {
    @apply bg-red-600 hover:bg-red-700 text-white focus:ring-red-500;
}

/* Estilos responsivos */
@screen sm {
    .calendar-day {
        @apply h-32;
    }
}

@screen md {
    .calendar-day {
        @apply h-40;
    }
}

@screen lg {
    .calendar-day {
        @apply h-48;
    }
}

/* Animações */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.fade-in {
    animation: fadeIn 0.3s ease-in-out;
}

/* Estilos de loading */
.loading-spinner {
    @apply animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500;
}

/* Estilos de notificação */
.notification {
    @apply fixed bottom-4 right-4 p-4 rounded-lg shadow-lg;
}

.notification.success {
    @apply bg-green-100 text-green-800 border-l-4 border-green-500;
}

.notification.error {
    @apply bg-red-100 text-red-800 border-l-4 border-red-500;
}

.notification.warning {
    @apply bg-yellow-100 text-yellow-800 border-l-4 border-yellow-500;
}

/* Estilos de tabela */
.table-container {
    @apply overflow-x-auto;
}

.table {
    @apply min-w-full divide-y divide-gray-200;
}

.table th {
    @apply px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider;
}

.table td {
    @apply px-6 py-4 whitespace-nowrap text-sm text-gray-500;
}

.table tr {
    @apply hover:bg-gray-50;
}

/* Estilos de card */
.card {
    @apply bg-white rounded-lg shadow-sm overflow-hidden;
}

.card-header {
    @apply p-4 border-b;
}

.card-body {
    @apply p-4;
}

.card-footer {
    @apply p-4 border-t;
}
``` 