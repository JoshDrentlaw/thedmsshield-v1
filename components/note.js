import React, { useState, useRef, useEffect } from 'react'
import styled from 'styled-components'
import fetch from 'isomorphic-unfetch'
import { Button, Form, Icon, Modal, Loader, Popup } from 'semantic-ui-react'
import { EditorState, RichUtils, convertToRaw, convertFromRaw } from 'draft-js'
import window from 'global'
//import PNotify from 'pnotify/dist/es/PNotify'
//import PNotifyButtons from 'pnotify/dist/es/PNotifyButtons'

import NoteEditor from './note-editor'
import MarkerEditor from './marker-editor'

const Header = styled(Modal.Header)`
    @media(min-width: 1024px) {
        font-size: 30px !important;
    }
`

const Marker = styled.div.attrs(props => ({
    x: props.left - (props.width / 2),
    y: props.top - (props.height / 2)
}))`
    top: ${props => props.y}px;
    left: ${props => props.x}px;
    width: ${props => props.width || 50}px;
    height: ${props => props.height || 50}px;
    border-radius: 999999px;
    background-color: black;
    position: absolute;
    transition: opacity 300ms cubic-bezier(.18,.03,.83,.95);
    opacity: ${props => props.opacity};
    box-shadow: ${props => props.shadow ? '0 0 0px 5px #039BE5' : 'none'};
    touch-action: none;
    user-select: none;

    &:hover {
        cursor: pointer;
    }
`

const Note = (props) => {
    const [editNote, setEditNote] = useState(false);
    const [editMarker, setEditMarker] = useState(false)
    const [active, setActive] = useState();
    const [title, setTitle] = useState(props.title);
    const [body, setBody] = useState(props.body);
    const [loading, setLoading] = useState(false);
    const content = convertFromRaw(JSON.parse(body))
    const [editorState, setEditorState] = useState(EditorState.createWithContent(content)) //.createEmpty()
    const [top, setTop] = useState(props.top)
    const [left, setLeft] = useState(props.left)
    const [width, setWidth] = useState(props.width)
    const [height, setHeight] = useState(props.height)

    const editor = useRef(null)

    useEffect(() => {
        window.onbeforeunload = editNote ? () => "" : undefined
    }, [editNote])

    useEffect(() => {
        setTop(props.top)
        setLeft(props.left)
        setWidth(props.width)
        setHeight(props.height)
    }, [props.top, props.left, props.width, props.height])

    const saveNote = () => {
        const url = process.env.URL + props._id
        if (title !== props.title || body !== props.body) {
            setLoading(true)
            fetch(`/api/markers/${props._id}`, {
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
        <>{
            editMarker ?
                <Popup
                    open
                    basic
                    pinned
                    position="top center"
                    trigger={
                        <Marker
                            data-id={props._id}
                            top={top}
                            left={left}
                            width={width}
                            height={height}
                            opacity={0.6}
                            shadow={true}
                            //ref={this.marker}
                            className="marker"
                        />
                    }
                    content={<MarkerEditor
                        editMarker={editMarker}
                        setEditMarker={setEditMarker}
                        type={props.type}
                        top={top}
                        setTop={setTop}
                        left={left}
                        setLeft={setLeft}
                        width={width}
                        setWidth={setWidth}
                        height={height}
                        setHeight={setHeight}
                        zoom={props.zoom}
                        _id={props._id}
                    />}
                />
            :
                <Modal
                    trigger={
                        <Marker
                            data-id={props._id}
                            top={props.top}
                            left={props.left}
                            width={props.width}
                            height={props.height}
                            opacity={props.opacity}
                            //ref={this.marker}
                            className="marker"
                        />
                    }
                    closeOnEscape={(!editNote && !editMarker)}
                    closeOnDimmerClick={(!editNote && !editMarker)}
                    closeIcon={(!editNote && !editMarker)}
                    centered={!editMarker}
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
                                (<Button size="small" disabled={editNote} color="blue" onClick={() => setEditMarker(!editMarker)}>
                                    <Icon name="target" />
                                    Edit Marker
                                </Button>)
                        }
                        {
                            loading ?
                                <Loader />
                                :
                                (<Button size="small" disabled={editMarker} color="blue" onClick={() => saveNote()}>
                                    <Icon name={editNote ? "save" : "edit"} />
                                    {editNote ? "Save" : "Edit Note"}
                                </Button>)
                        }
                    </Modal.Actions>
                </Modal>
        }</>
    )
}

export default Note;