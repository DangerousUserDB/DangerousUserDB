function alertBot() {
    halfmoon.toggleDarkMode()
    Swal.fire({
      title: '<strong>New! Discord Bot</strong>',
      icon: 'info',
      html: 'Get our Discord Bot! <a href="https://discord.com/oauth2/authorize?client_id=764485265775263784&scope=bot&permissions=3072">Click here</a>',
      showCloseButton: true,
      focusConfirm: false,
      confirmButtonText:
        '<i class="fa fa-thumbs-up"></i> Great!',
      confirmButtonAriaLabel: 'Thumbs up, great!',
    })
     // Script also toggles HalfMoon's Dark Theme. In a future update, this will be configurable on the dashboard.
}