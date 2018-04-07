// instanciate new modal
var modal = new tingle.modal({
    footer: true,
    stickyFooter: false,
    closeMethods: ['overlay', 'button', 'escape'],
    closeLabel: "Close",
    cssClass: ['custom-class-1', 'custom-class-2'],
    onOpen: function() {
        console.log('modal open');
    },
    onClose: function() {
        console.log('modal closed');
    },
    beforeClose: function() {
        // here's goes some logic
        // e.g. save content before closing the modal
        return true; // close the modal
        return false; // nothing happens
    }
});

// set content
modal.setContent('<label class="decal" for="pseudo">Pseudo</label><br/>' +
    '<input  class="decal" type="text" name="pseudo" id="pseudo" size="30" maxlength="11"/><br/>' +
    '<label  class="decal" for="mail">E-mail</label><br/>' +
    '<input  class="decal" type="text" name="mail" id="mail" size="30" maxlength="40" /><br/>' +

    '<label  class="decal" for="password">Mot de passe</label><br/>' +
    '<input  class="decal" type="password" name="password" id="password" size="30" maxlength="20" /><br/>' +

    '<label  class="decal" for="conf_pass">Confirmation du mot de passe</label><br/>' +
    '<input  class="decal" type="password" name="conf_pass" id="conf_pass" size="30" maxlength="20" /><br/>');

// add a button
modal.addFooterBtn('Button label', 'tingle-btn tingle-btn--primary', function() {
    // here goes some logic
    modal.close();
});

// add another button
modal.addFooterBtn('Dangerous action !', 'tingle-btn tingle-btn--danger', function() {
    // here goes some logic
    modal.close();
});

// open modal
modal.open();

// close modal
// modal.close();
