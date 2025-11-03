<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gemini Editor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.1.0/css/all.min.css">
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            background-color: #1a1a1a;
            color: #f0f0f0;
            font-family: sans-serif;
        }

        #menu-bar {
            background-color: #252525;
            padding: 5px;
        }

        #menu-bar h1 {
            font-size: 1.2em;
            color: #ff9900;
            margin: 0 20px 0 10px;
        }

        #menu-bar button {
            background-color: #ff9900;
            color: #1a1a1a;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        #editor-container {
            display: flex;
            height: 100%;
        }

        #main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        #tab-bar {
            display: flex;
            background-color: #252525;
            padding: 5px;
        }

        .tab {
            padding: 10px 15px;
            cursor: pointer;
            border-right: 1px solid #333;
            position: relative;
        }

        .tab.active {
            background-color: #1a1a1a;
        }

        .close-tab {
            position: absolute;
            top: 5px;
            right: 5px;
            cursor: pointer;
        }

        #add-tab {
            padding: 10px 15px;
            cursor: pointer;
            background-color: #ff9900;
            color: #1a1a1a;
        }

        #editor {
            flex-grow: 1;
            height: 100%;
        }

        #chat-container {
            width: 30%;
            max-width: 400px;
            background-color: #252525;
            display: flex;
            flex-direction: column;
        }

        #chat-messages {
            flex-grow: 1;
            padding: 10px;
            overflow-y: auto;
        }

        #chat-input {
            border: none;
            padding: 10px;
            background-color: #333;
            color: #f0f0f0;
        }

        .ace_editor {
            font-size: 16px;
        }

        .ace-tomorrow-night-bright .ace_gutter {
            background: #252525;
            color: #ccc;
        }

        .ace-tomorrow-night-bright .ace_print-margin {
            width: 1px;
            background: #444;
        }

        .ace-tomorrow-night-bright {
            background-color: #1a1a1a;
            color: #f0f0f0;
        }

        .ace-tomorrow-night-bright .ace_cursor {
            color: #ff9900;
        }

        .ace-tomorrow-night-bright .ace_marker-layer .ace_selection {
            background: #444;
        }

        .ace-tomorrow-night-bright.ace_multiselect .ace_selection.ace_start {
            box-shadow: 0 0 3px 0px #1a1a1a;
        }

        .ace-tomorrow-night-bright .ace_marker-layer .ace_step {
            background: rgb(102, 82, 0);
        }

        .ace-tomorrow-night-bright .ace_marker-layer .ace_bracket {
            margin: -1px 0 0 -1px;
            border: 1px solid #666;
        }

        .ace-tomorrow-night-bright .ace_marker-layer .ace_active-line {
            background: #2a2a2a;
        }

        .ace-tomorrow-night-bright .ace_gutter-active-line {
            background-color: #2a2a2a;
        }

        .ace-tomorrow-night-bright .ace_marker-layer .ace_selected-word {
            border: 1px solid #ff9900;
        }

        .ace-tomorrow-night-bright .ace_invisible {
            color: #444;
        }

        .ace-tomorrow-night-bright .ace_keyword,
        .ace-tomorrow-night-bright .ace_meta,
        .ace-tomorrow-night-bright .ace_storage,
        .ace-tomorrow-night-bright .ace_storage.ace_type,
        .ace-tomorrow-night-bright .ace_support.ace_type {
            color: #ff9900;
        }

        #diff-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 100;
        }

        #diff-container {
            display: flex;
            width: 80%;
            height: 70%;
            margin: 5% auto;
            background-color: #1a1a1a;
            padding: 20px;
        }

        .diff-column {
            flex-grow: 1;
            padding: 10px;
            display: flex;
            flex-direction: column;
        }

        .diff-column textarea {
            width: 100%;
            height: 100%;
            background-color: #252525;
            color: #f0f0f0;
            border: none;
        }

        #run-diff {
            background-color: #ff9900;
            color: #1a1a1a;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
        }

        #diff-output {
            width: 80%;
            margin: 20px auto;
            background-color: #252525;
            padding: 20px;
            height: 20%;
            overflow-y: auto;
        }

        #close-diff {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #fff;
            font-size: 30px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div id="menu-bar">
        <h1><i class="fas fa-tshirt"></i> Gemini Editor</h1>
        <button id="load-local">Load</button>
        <button id="save-local">Save</button>
        <button id="load-server">Load from Server</button>
        <button id="save-server">Save to Server</button>
        <button id="send-to-gemini">Send to Gemini</button>
        <button id="diff">Diff</button>
    </div>

    <div id="diff-modal" style="display:none;">
        <div id="diff-container">
            <div class="diff-column">
                <h3>Original Code</h3>
                <textarea id="diff-original"></textarea>
            </div>
            <div class="diff-column">
                <h3>Modified Code</h3>
                <textarea id="diff-modified"></textarea>
            </div>
        </div>
        <button id="run-diff">Run Diff</button>
        <div id="diff-output"></div>
        <span id="close-diff">&times;</span>
    </div>

    <div id="editor-container">
        <div id="main-content">
            <div id="tab-bar">
                <div id="add-tab">+</div>
            </div>
            <div id="editor"></div>
        </div>
        <div id="chat-container">
            <div id="chat-messages"></div>
            <input type="text" id="chat-input" placeholder="Chat with Gemini...">
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/diff_match_patch/20121119/diff_match_patch.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
    <script>
        const tabs = {};
        let currentTab = null;
        let untitledCount = 1;

        function createTab(filename, content = '') {
            if (!filename) {
                filename = `untitled-${untitledCount++}.php`;
            }

            const tabId = `tab-${Object.keys(tabs).length}`;
            const tab = document.createElement('div');
            tab.className = 'tab';
            tab.textContent = filename;
            tab.onclick = () => switchTab(tabId);

            const closeButton = document.createElement('span');
            closeButton.className = 'close-tab';
            closeButton.textContent = 'x';
            closeButton.onclick = (e) => {
                e.stopPropagation();
                closeTab(tabId);
            };
            tab.appendChild(closeButton);

            const tabBar = document.getElementById('tab-bar');
            tabBar.insertBefore(tab, document.getElementById('add-tab'));

            const editorSession = ace.createEditSession(content, 'ace/mode/php');
            tabs[tabId] = {
                id: tabId,
                filename: filename,
                session: editorSession,
                element: tab
            };
            return tabId;
        }

        function switchTab(tabId) {
            if (currentTab) {
                tabs[currentTab].element.classList.remove('active');
            }
            currentTab = tabId;
            tabs[currentTab].element.classList.add('active');
            editor.setSession(tabs[tabId].session);
        }

        function closeTab(tabId) {
            const tab = tabs[tabId];
            document.getElementById('tab-bar').removeChild(tab.element);
            delete tabs[tabId];

            if (currentTab === tabId) {
                const remainingTabs = Object.keys(tabs);
                if (remainingTabs.length > 0) {
                    switchTab(remainingTabs[0]);
                } else {
                    editor.setSession(ace.createEditSession('', 'ace/mode/php'));
                    currentTab = null;
                }
            }
        }

        var editor = ace.edit("editor");
        editor.setTheme("ace/theme/tomorrow_night_bright");

        document.getElementById('add-tab').onclick = () => {
            const newTabId = createTab();
            switchTab(newTabId);
        };

        document.getElementById('load-local').onclick = () => {
            const input = document.createElement('input');
            input.type = 'file';
            input.onchange = (e) => {
                const file = e.target.files[0];
                const reader = new FileReader();
                reader.onload = (e) => {
                    const newTabId = createTab(file.name, e.target.result);
                    switchTab(newTabId);
                };
                reader.readAsText(file);
            };
            input.click();
        };

        document.getElementById('save-local').onclick = () => {
            if (currentTab) {
                const tab = tabs[currentTab];
                const blob = new Blob([tab.session.getValue()], {
                    type: "text/plain;charset=utf-8"
                });
                saveAs(blob, tab.filename);
            }
        };

        document.getElementById('load-server').onclick = () => {
            fetch('file-handler.php?action=list')
                .then(response => response.json())
                .then(files => {
                    const filename = prompt('Select a file to load:\n' + files.join('\n'));
                    if (filename) {
                        fetch('file-handler.php?action=get', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `filename=${filename}`
                            })
                            .then(response => response.text())
                            .then(content => {
                                const newTabId = createTab(filename, content);
                                switchTab(newTabId);
                            });
                    }
                });
        };

        document.getElementById('save-server').onclick = () => {
            if (currentTab) {
                const tab = tabs[currentTab];
                fetch('file-handler.php?action=save', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `filename=${tab.filename}&content=${encodeURIComponent(tab.session.getValue())}`
                    })
                    .then(response => response.text())
                    .then(message => {
                        alert(message);
                    });
            }
        };

        document.getElementById('send-to-gemini').onclick = () => {
            if (currentTab) {
                const tab = tabs[currentTab];
                const code = tab.session.getValue();

                const userMessage = document.createElement('div');
                userMessage.textContent = `You: (sent code from ${tab.filename})`
                chatMessages.appendChild(userMessage);

                fetch('gemini-handler.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `message=${encodeURIComponent(code)}`
                    })
                    .then(response => response.text())
                    .then(response => {
                        const geminiMessage = document.createElement('div');
                        geminiMessage.textContent = `Gemini: ${response}`;
                        chatMessages.appendChild(geminiMessage);
                    });
            }
        };

        const chatMessages = document.getElementById('chat-messages');
        const chatInput = document.getElementById('chat-input');

        chatInput.onkeydown = (e) => {
            if (e.key === 'Enter') {
                const message = chatInput.value;
                chatInput.value = '';

                const userMessage = document.createElement('div');
                userMessage.textContent = `You: ${message}`;
                chatMessages.appendChild(userMessage);

                fetch('gemini-handler.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `message=${encodeURIComponent(message)}`
                    })
                    .then(response => response.text())
                    .then(response => {
                        const geminiMessage = document.createElement('div');
                        geminiMessage.textContent = `Gemini: ${response}`;
                        chatMessages.appendChild(geminiMessage);
                    });
            }
        };

        const diffModal = document.getElementById('diff-modal');
        document.getElementById('diff').onclick = () => {
            diffModal.style.display = 'block';
        };

        document.getElementById('run-diff').onclick = () => {
            const dmp = new diff_match_patch();
            const text1 = document.getElementById('diff-original').value;
            const text2 = document.getElementById('diff-modified').value;
            const d = dmp.diff_main(text1, text2);
            dmp.diff_cleanupSemantic(d);
            const ds = dmp.diff_prettyHtml(d);
            document.getElementById('diff-output').innerHTML = ds;
        };

        document.getElementById('close-diff').onclick = () => {
            diffModal.style.display = 'none';
        };

        const initialTab = createTab('untitled.php', `<?php\n// Welcome to Gemini Editor!\n?>`);
        switchTab(initialTab);
    </script>

</body>

</html>
