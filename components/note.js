import React, { useState, useRef, useEffect } from 'react'
import styled from 'styled-components'
import fetch from 'isomorphic-unfetch'
import { Button, Form, Icon, Modal, Loader } from 'semantic-ui-react'
import { EditorState, RichUtils, convertToRaw, convertFromRaw } from 'draft-js'
//import PNotify from 'pnotify/dist/es/PNotify'
//import PNotifyButtons from 'pnotify/dist/es/PNotifyButtons'

import NoteEditor from './editor'

const Header = styled(Modal.Header)`
    @media(min-width: 1024px) {
        font-size: 30px !important;
    }
`

const Note = (props) => {
    const [editNote, setEditNote] = useState(false);
    const [editMarker, setEditMarker] = useState(false);
    const [active, setActive] = useState();
    const [title, setTitle] = useState(props.title);
    const [body, setBody] = useState(props.body);
    const [loading, setLoading] = useState(false);
    const content = convertFromRaw(JSON.parse(body))
    const [editorState, setEditorState] = useState(EditorState.createWithContent(content)) //.createEmpty()

    const editor = useRef(null)

    useEffect(() => {
        window.onbeforeunload = editNote ? () => "Your work will be lost!" : undefined
    }, [editNote])

    const saveNote = () => {
        const url = process.env.URL + props._id
        if (title !== props.title || body !== props.body) {
            setLoading(true)
            fetch(url, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ note_title: title, note_body: body })
            })
            .then(res => {
                console.log(res)
                const { status } = res
                if (status === 200) {
                    setEditNote(!editNote)
                    setLoading(false)
                    /* PNotify.success({
                        title: "Success!",
                        text: "Note successfully updated!",
                        Animate: {
                            animate: true,
                            inClass: 'flipInY',
                            outClass: 'flipOutY'
                        }
                    }) */
                }
            })
        }
        else {
            setEditNote(!editNote)
        }
    }

    const onChange = (editorState) => {
        switch (active) {
            case 'BOLD':
                RichUtils.toggleInlineStyle(editorState, active)
                break;
            default:
                setEditorState(editorState)
        }
        const content = editorState.getCurrentContent()
        const raw = JSON.stringify(convertToRaw(content))
        setBody(raw)
    }

    const handleKeyCommand = (command) => {
        const newState = RichUtils.handleKeyCommand(editorState, command)

        if (newState) {
            onChange(newState)
            return 'handled'
        }
        return 'not handled'
    }

    const buttonCommand = (command) => {
        editor.current.focus()
        if (active === command) {
            setActive(null);
        }
        else {
            setActive(command);
        }
    }

    return (
        <Modal
            trigger={props.children}
            closeOnEscape={editNote ? false : true}
            closeOnDimmerClick={editNote ? false : true}
            closeIcon={editNote ? false : true}
        >
            <Header>
                {editNote ?
                    <Form.Input value={title} onChange={({ target }) => setTitle(target.value)} />
                    : title
                }
            </Header>
            <Modal.Content>
                <Modal.Description>
                    <NoteEditor
                        editor={editor}
                        editNote={editNote}
                        onChange={onChange}
                        handleKeyCommand={handleKeyCommand}
                        buttonCommand={buttonCommand}
                        active={active}
                        editorState={editorState}
                    />
                </Modal.Description>
            </Modal.Content>
            <Modal.Actions>
                    {
                        loading ?
                            <Loader />
                            :
                            (<Button color="blue" onClick={() => setEditMarker(!editMarker)}>
                                <Icon name={editMarker ? "save" : "target"} />
                                {editMarker ? "Save" : "Edit Marker"}
                            </Button>)
                    }
                    {
                        loading ?
                            <Loader />
                            :
                            (<Button color="blue" onClick={() => saveNote()}>
                                <Icon name={editNote ? "save" : "edit"} />
                                {editNote ? "Save" : "Edit Note"}
                            </Button>)
                    }
            </Modal.Actions>
        </Modal>
    )
}

export default Note;