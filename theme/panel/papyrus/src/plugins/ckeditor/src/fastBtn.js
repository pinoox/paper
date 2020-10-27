import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import ButtonView from '@ckeditor/ckeditor5-ui/src/button/buttonview';
import Command from '@ckeditor/ckeditor5-core/src/command';

class MyCommand extends Command {

    constructor(editor, commands, view) {
        super(editor);
        this.commands = !!commands && typeof commands === 'object' ? commands : {};
        this.view = view;
    }

    execute(message) {
        if (!!this.commands.execute && typeof this.commands.execute === 'function')
            this.commands.execute(this.view, this.editor, message);
    }

    refresh() {
        if (!!this.commands.refresh && typeof this.commands.refresh === 'function')
            this.commands.refresh(this.view, this.editor);
    }
}

/**
 * The FastBtn feature. It introduces the FastBtn button.
 *
 * @extends module:core/plugin~Plugin
 */
export default class FastBtn extends Plugin {
    /**
     * @inheritDoc
     */
    init() {
        const options = this.editor.config.get('fastBtn');
        if (!options) {
            return;
        }

        for (const option of options) {
            let name = this.getValue(option, 'name');
            let replace = this.getValue(option, 'replace');
            if (!replace)
                this.addBtn(name, option);
            else
                this.replaceBtn(name, option);
        }
    }

    getConfig(option) {
        let config = {};
        for (let type in option) {
            if (type === 'name')
                continue;
            let value = this.getValue(option, type);
            if (typeof value === 'boolean' || !!value)
                config[type] = value;
        }

        return config;
    }

    getValue(option, name, valueDefault = null) {
        let result = typeof option[name] === 'boolean' || !!option[name] ? option[name] : valueDefault;
        result = name !== 'refresh' && name !== 'execute' && name !== 'mounted' && name !== 'created' && name !== 'action' && typeof result === 'function' ? result(this.editor) : result;
        return result;
    }

    addBtn(name, option) {
        let editor = this.editor;
        name = 'fastBtn:' + name;
        editor.ui.componentFactory.add(name, locale => {
            let action = this.getValue(option, 'action');
            let refresh = this.getValue(option, 'refresh');
            let config = this.getConfig(option);
            const view = new ButtonView(locale);
            view.set(config);
            editor.commands.add(name, new MyCommand(editor, {
                execute: action,
                refresh: refresh,
            }, view));

            let created = this.getValue(option, 'created');
            let mounted = this.getValue(option, 'mounted');

            view.render();

            if (!!created)
                created(view, editor, config);

            let actionFunc = () => {
                action(view, editor, config);
            };
            view.on('execute', actionFunc);
            editor.keystrokes.set(config.keystroke, actionFunc);

            if (!!mounted)
                mounted(view, editor, config);

            return view;
        });
    }

    replaceBtn(name, option) {
        let created = this.getValue(option, 'created');
        let mounted = this.getValue(option, 'mounted');
        let plugin = this.getValue(option, 'plugin');

        if (plugin === 'BlockToolbar' && name === '+') {
            this.replaceMainBlockBtn(option);
            return;
        }
        let editor = this.editor;
        editor.on('ready', () => {
            let itemsToolbar = this.getItemsToolbar(plugin);
            let configToolbar = this.getConfigToolbar(plugin);
            let config = this.getConfig(option);

            for (let index in configToolbar) {
                let value = configToolbar[index];
                if (value === name && !!itemsToolbar[index]) {
                    let view = itemsToolbar[index];
                    if (!!created)
                        created(view, editor, config);
                    Object.assign(view, config);
                    if (!!mounted)
                        mounted(view, editor, config);
                }
            }
        });
    }

    getConfigToolbar(plugin = null) {
        let editor = this.editor;
        let name = 'toolbar';
        if (plugin === 'BlockToolbar')
            name = 'blockToolbar';
        else if (plugin === 'BalloonToolbar')
            name = 'balloonToolbar';
        let config = editor.config.get(name);
        return typeof config === 'object' && !!config['items'] ? config['items'] : config;
    }

    getItemsToolbar(plugin = null) {
        let editor = this.editor;
        let toolbar = editor.ui.view.toolbar;
        if (plugin === 'BlockToolbar')
            toolbar = editor.plugins.get('BlockToolbar').toolbarView;
        else if (plugin === 'BalloonToolbar')
            toolbar = editor.plugins.get('BalloonToolbar').toolbarView;
        return toolbar.items._items;
    }

    replaceMainBlockBtn(option) {
        let editor = this.editor;
        let view = editor.plugins.get('BlockToolbar').buttonView;

        let created = this.getValue(option, 'created');
        let mounted = this.getValue(option, 'mounted');
        let config = this.getConfig(option);

        if (!!created)
            created(view, editor, config);
        Object.assign(view, config);
        if (!!mounted)
            mounted(view, editor, config);
    }

    /**
     * @inheritDoc
     */
    static get pluginName() {
        return 'FastBtn';
    }
}
