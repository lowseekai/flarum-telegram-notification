import app from 'flarum/admin/app';

app.initializers.add('nodeloc-telegram-notification', () => {
  app.registry
    .for('nodeloc-telegram-notification')
    .registerSetting({
      setting: 'telegram.bot_token',
      label: app.translator.trans('nodeloc-telegram-notification.admin.settings.bot_token_label'),
      help: app.translator.trans('nodeloc-telegram-notification.admin.settings.bot_token_help'),
      type: 'text',
    })
    .registerSetting({
      setting: 'telegram.channel_id',
      label: app.translator.trans('nodeloc-telegram-notification.admin.settings.channel_id_label'),
      help: app.translator.trans('nodeloc-telegram-notification.admin.settings.channel_id_help'),
      type: 'text',
    })
    .registerSetting({
      setting: 'telegram.excluded_tags',
      label: app.translator.trans('nodeloc-telegram-notification.admin.settings.excluded_tags_label'),
      help: app.translator.trans('nodeloc-telegram-notification.admin.settings.excluded_tags_help'),
      type: 'text',
    });
});
