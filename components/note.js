import React, { useState, useRef } from 'react'
import styled from 'styled-components'
import fetch from 'isomorphic-unfetch'
import { Button, Form, Icon, Modal, Loader } from 'semantic-ui-react'
import { EditorState, RichUtils, ContentState, convertToRaw, convertFromRaw, convertFromHTML } from 'draft-js'
import Editor from 'draft-js-plugins-editor'
import createHighlightPlugin from './plugins/highlightPlugin'
//import PNotify from 'pnotify/dist/es/PNotify'
//import PNotifyButtons from 'pnotify/dist/es/PNotifyButtons'

const EditorContainer = styled.div`
    border: ${props => props.edit ? '1px solid #cdcdcd' : 'none'};
    border-radius: 5px;
    margin-top: ${props => props.edit ? '1em' : 0};
    padding: ${props => props.edit ? '1em' : 0};
    height: ${props => props.edit ? '50vh' : 'auto'};

    & > div {
        height: 100%;

        & > div {
            height: 100%;

            & > div {
                height: 100%;
            }
        }
    }
`

const Note = (props) => {
    const [edit, setEdit] = useState(false);
    const [active, setActive] = useState();
    const [title, setTitle] = useState(props.title);
    const [body, setBody] = useState(props.body);
    const [loading, setLoading] = useState(false);

    const content = convertFromRaw(JSON.parse(body))
    const [editorState, setEditorState] = useState(EditorState.createWithContent(content)) //.createEmpty()

    const hightlightPlugin = createHighlightPlugin()
    let plugins = [
        hightlightPlugin,
    ]

    const saveNote = () => {
        const url = (process.env.NODE_ENV === 'production' ? `https://thedmsshield.com/api/markers/${props._id}` : `http://localhost:3000/api/markers/${props._id}`)
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
                    setEdit(!edit)
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
            setEdit(!edit)
        }
    }

    const onChange = (editorState) => {
        setEditorState(editorState)
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
        onChange(RichUtils.toggleInlineStyle(editorState, command));
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
            closeOnEscape={edit ? false : true}
            closeOnDimmerClick={edit ? false : true}
            closeIcon={edit ? false : true}
        >
            <Modal.Header>
                {edit ?
                    <Form.Input value={title} onChange={({ target }) => setTitle(target.value)} />
                    : title
                }
            </Modal.Header>
            <Modal.Content>
                <Modal.Description>
                    {edit ?
                        <>
                            <Button.Group>
                                <Button icon>
                                    <Icon name='align left' />
                                </Button>
                                <Button icon>
                                    <Icon name='align center' />
                                </Button>
                                <Button icon>
                                    <Icon name='align right' />
                                </Button>
                                <Button icon>
                                    <Icon name='align justify' />
                                </Button>
                            </Button.Group>{' '}
                            <Button.Group>
                                <Button icon toggle active={active === 'BOLD' ? true : false} onClick={() => buttonCommand('BOLD')}>
                                    <Icon name='bold' />
                                </Button>
                                <Button icon toggle active={active === 'UNDERLINE' ? true : false} onClick={() => buttonCommand('UNDERLINE')}>
                                    <Icon name='underline' />
                                </Button>
                                <Button icon toggle active={active === 'ITALIC' ? true : false} onClick={() => buttonCommand('ITALIC')}>
                                    <Icon name='italic' />
                                </Button>
                            </Button.Group>
                        </>
                        : null
                    }
                    <EditorContainer edit={edit}>
                        <Editor
                            editorState={editorState}
                            onChange={onChange}
                            handleKeyCommand={handleKeyCommand}
                            plugins={plugins}
                            readOnly={edit ? false : true}
                        />
                    </EditorContainer>
                </Modal.Description>
            </Modal.Content>
            <Modal.Actions>
                    {
                        loading ?
                            <Loader />
                            :
                            (<Button color="blue" onClick={() => saveNote()}>
                                <Icon name={edit ? "save" : "edit"} />
                                {edit ? "Save" : "Edit"}
                            </Button>)
                    }
            </Modal.Actions>
        </Modal>
    )
}

export default Note;