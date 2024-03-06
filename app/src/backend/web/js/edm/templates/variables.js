// Кнопка просмотра шаблона платежных поручений
var paymentOrderTemplateViewBtn = $('.edm-template-po-view-modal-btn');

// Кнопка редактирования шаблона платежного поручения в модальном окне
var paymentOrderTemplateModalUpdateBtn = '#edmTemplatePOViewModal .edm-template-view-modal-update';

// Кнопка редактирования шаблона платежного поручения в журнале шаблонов
var paymentOrderTemplateJournalUpdateBtn = $('.edm-template-po-modal-update');

// Кнопка создания нового шаблона платежных поручений
var paymentOrderTemplateNewBtn = $('.edm-template-new-po-modal-btn');

// Модальное окно просмотра шаблона платежных поручений
var paymentOrderTemplateViewModal = $('#edmTemplatePOViewModal');

// Модальное окно редактирования/создания шаблона платежных поручений
var paymentOrderTemplateFormModal = $('#edmTemplatePOModal');

// Url получения содержимого для просмотра шаблона платежного поручения
var paymentOrderTemplateViewUrl = '/edm/payment-order-templates/payment-order-get-template-view?id=';

// Url удаления шаблона платежных поручений
var paymentOrderTemplateDeleteUrl = '/edm/payment-order-templates/payment-order-delete?id=';

// Url создания нового платежного поручения из шаблона
var paymentOrderTemplateCreateUrl = '/edm/payment-order-templates/create-payment-order?id=';

// Url получения формы создания/редактирования шаблона платежного поручения
var paymentOrderTemplateFormUrl = '/edm/payment-order-templates/payment-order-get-new-template-form?id=';

//----------------------------------------------------

// Кнопка просмотра шаблона валютных платежей
var fcpTemplateViewBtn = $('.edm-template-fcp-view-modal-btn');

// Url получения содержимого для просмотра шаблона валютных платежей
var fcpTemplateViewUrl = '/edm/payment-order-templates/fcp-get-template-view?id=';

// Модальное окно просмотра шаблона валютных платежей
var fcpTemplateViewModal = $('#edmTemplateFCPViewModal');

// Url удаления шаблона валютных платежей
var fcpTemplateDeleteUrl = '/edm/payment-order-templates/fcp-delete?id=';

// Url создания нового валютного платежа из шаблона
var fcpTemplateCreateUrl = '/edm/currency-payment/payment-index?template=';

// Кнопка редактирования шаблона валютного платежа в журнале шаблонов
var fcpTemplateJournalUpdateBtn = $('.edm-template-fcp-modal-update');

// Модальное окно редактирования/создания шаблона валютных платежей
var fcpTemplateFormModal = $('#edmTemplateFCPModal');

// Url получения формы создания/редактирования шаблона валютного платежа
var fcpTemplateFormUrl = '/edm/payment-order-templates/fcp-get-new-template-form?id=';

// Кнопка редактирования шаблона валютного платежа в модальном окне
var fcpTemplateModalUpdateBtn = '#edmTemplateFCPViewModal .edm-template-view-modal-update';

// Кнопка создания нового шаблона валютных платежей
var fcpTemplateNewBtn = $('.edm-template-new-fcp-modal-btn');