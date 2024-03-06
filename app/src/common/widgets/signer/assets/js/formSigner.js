var formSigner = {
	o: {
		signedVar: 'signedRequest',
		signer: 'capicom',
		formId: null,
		isDetached: false,
//		visibleFieldTpl: "<div></div>",
		error: {
			// Сообщения об ошибках устанавливаются Yii виджетом
			//undefinedSigner: 'Ваш браузер не поддерживает ни один из имеющихся механизмов электронной подписи.',
			//brokenSigner: 'Ошибка работы драйвера подписи ',
			//emptyCertField: 'Необходимо выбрать сертификат'
		}
	},
	
	signer: null,
	form: null,
	fingerprintField: null,
	rawField: null,
	signField: null,
	serializableFields: [], // поля значения которых мы собираемся подписывать

	/**
	 * переменные для блокировки двойного срабатывания в ИЕ
	 */
	_submitCounter: 0,
	_spokeBlocker: 0,
	_prevSubmit: null,

	init: function(params) {
		if(params) {
			for(var key in params) {
				if(typeof(this.o[key]) !== "undefined" ) {
					this.o[key] = params[key];
				}
			}
		}
		
		if(typeof this.o.signer === 'undefined') {
			// @todo сценарий обработки
			alert(this.o.error.undefinedSigner);
			return null;
		}

		// инициализируем драйвер подписанта
		this.signer = signer[this.o.signer]();
		if(!this.signer) {
			// @todo сценарий обработки
			alert(this.o.error.brokenSigner);
			return null;
		}
		this._initForm();

		return this;
	},
	
	
	_sign: function() {
		if (!this.fingerprintField[0].value) {
			alert(this.o.error.emptyCertField);
			return false;
		}

		this.rawField[0].value = JSON.stringify(
			$(this.serializableFields).serializeObject()
		);

		this.signField[0].value = this.signer.sign(
			this.rawField[0].value,
			this.o.isDetached,
			this.fingerprintField[0].value
		);
		return true;
	},
	
	
	_initForm: function (){
		var self = this;
		
		var certs = this.signer.getCertificatesList();
		this.form = $(document.getElementById(this.o.formId));
		this._initSerializableFields();
		
		// выбираем сертификат которым подписываемся
		this.fingerprintField = $('<select name="' +this.o.signedVar + '[fingerprint]"/></select>');
		this.fingerprintField.append('<option value="">Выберите сертификат</option>');
		for (var i in certs) {
			this.fingerprintField.append('<option value="' + certs[i].thumbprint +'">' + certs[i].displayName + '</option>');
		}
		this.form.prepend(this.fingerprintField);
		
		// поля со значениями подписи и данных
		this.rawField	= $('<input type="hidden" name="' +this.o.signedVar + '[raw]" value=""/>').prependTo(this.form);
		this.signField	= $('<input type="hidden" name="' +this.o.signedVar + '[sign]" value=""/>').prependTo(this.form);
		
		// служебные поля
		this.form.prepend('<input type="hidden" name="' +this.o.signedVar + '[format]" value="' + this.signer.format + '"/>');
		this.form.prepend('<input type="hidden" name="' +this.o.signedVar + '[app]" value="' + this.o.signer + '"/>');
		this.form.prepend('<input type="hidden" name="' +this.o.signedVar + '[isDetached]" value="' + this.o.isDetached + '"/>');
		this.form.bind('submit', function (e) {
			/**
			 * Костыль от повторного срабатывания после загрузки страницы в IE
			 * @todo разобраться с двойным срабатыванием события в IE
			 */
			self._submitCounter++;
			if (self._submitCounter >= 2 && !self._spokeBlocker) {
				self._submitCounter = 0;
				self._spokeBlocker++;
				return self._prevSubmit;
			}

			return self._prevSubmit = self._sign();
		});
	},
	
	
	_initSerializableFields: function(){
		var self = this;
		$.each(this.form.serializeArray(), function (key, field) {
			if(field.name === '_csrf') {
				return;
			}
            self.serializableFields.push(field);
		});
	}
};