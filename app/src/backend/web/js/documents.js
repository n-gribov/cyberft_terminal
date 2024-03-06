function createDeleteDocumentsConfirmationMessage(documentsCount, language)
{
    var message = language == 'ru' ? 'Подтвердите удаление' : 'Please confirm deleting';

    var pluralizeRules = [
        { regexp: /11$/, word: { ru: 'документов', en: 'documents' } },
        { regexp: /1$/,  word: { ru: 'документа',  en: 'document' } },
        { regexp: /.*/,  word: { ru: 'документов', en: 'documents' } }
    ];

    var pluralizedEntityName;
    for (var i = 0; i < pluralizeRules.length; i++) {
        var rule = pluralizeRules[i];
        if (rule.regexp.test(documentsCount)) {
            pluralizedEntityName = rule.word[language];
            break;
        }
    }

    return message + ' ' + documentsCount + ' ' + pluralizedEntityName;
}
