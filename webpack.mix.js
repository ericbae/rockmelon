const mix         = require('laravel-mix')
const tailwindcss = require('tailwindcss')

mix.alias({
  '@': 'resources/js'
});

mix.js('resources/js/views/auth/app.js',                       'public/js/auth').vue()
mix.js('resources/js/views/user/home/app.js',                  'public/js/user/home').vue()
mix.js('resources/js/views/link-group/try/app.js',             'public/js/link-group/try').vue()
mix.js('resources/js/views/link-group/view/app.js',            'public/js/link-group/view').vue()
mix.js('resources/js/views/link-group/create/app.js',          'public/js/link-group/create').vue()
// mix.js('resources/js/views/account/onboarding/hello/app.js',          'public/js/account/onboarding/hello').vue()
// mix.js('resources/js/views/account/onboarding/keywords/app.js',       'public/js/account/onboarding/keywords').vue()
// mix.js('resources/js/views/account/onboarding/hooray/app.js',         'public/js/account/onboarding/hooray').vue()
// mix.js('resources/js/views/account/dashboard/app.js',                 'public/js/account/dashboard').vue()
// mix.js('resources/js/views/account/keyword/app.js',                   'public/js/account/keyword').vue( )
// mix.js('resources/js/views/account/recommendation/app.js',            'public/js/account/recommendation').vue( )
// mix.js('resources/js/views/account/delete/app.js',                    'public/js/account/delete').vue()
// mix.js('resources/js/views/user/account/app.js',                      'public/js/user/account').vue()
// mix.js('resources/js/views/user/billing/app.js',   'public/js/user/billing').vue()

mix.js('resources/js/shared/embeddable-components/contact-us/app.js', 'public/js/shared/embeddable-components/contactus').vue()

mix.less('resources/less/app.less', 'public/css')
  .options({
    postCss: [
      tailwindcss('./tailwind.config.js'),
    ]
  })


// mix.js('resources/js/views/auth/app.js',                                              'public/js/auth')
// mix.js('resources/js/views/home/app.js',                                              'public/js/home')
// mix.js('resources/js/views/digest/app.js',                                        'public/js/digest')
// mix.js('resources/js/views/test/app.js',                                          'public/js/test')
// mix.js('resources/js/views/editor/app.js',                                        'public/js/editor')


// mix.js('resources/js/views/account/keyword/app.js',                'public/js/account/keyword')