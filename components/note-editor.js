import React from 'react'
import styled from 'styled-components'
import { Button, Icon } from 'semantic-ui-react'
import Editor from 'draft-js-plugins-editor'
import createHighlightPlugin from './plugins/highlightPlugin'
import 'draft-js/dist/Draft.css'

const EditorContainer = styled.div`
    border: ${props => props.editNote ? '1px solid #cdcdcd' : 'none'};
    border-radius: 5px;
    margin-top: ${props => props.editNote ? '1em' : 0};
    padding: ${props => props.editNote ? '1em' : 0};
    height: ${props => props.editNote ? '50vh' : 'auto'};
    max-height: 60vh;
    overflow-y: scroll;

    @media(min-width: 1024px) {
        font-size: 22px;
    }

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

const NoteEditor = (props) => {
    const {
        editNote, onChange, handleKeyCommand, buttonCommand,
        active, editorState, editor
    } = props

    const hightlightPlugin = createHighlightPlugin()
    let plugins = [
        hightlightPlugin,
    ]

    return (
        <>
            {editNote &&
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
                        <Button icon toggle active={active === 'BOLD'} onClick={() => buttonCommand('BOLD')}>
                            <Icon name='bold' />
                        </Button>
                        <Button icon toggle active={active === 'UNDERLINE'} onClick={() => buttonCommand('UNDERLINE')}>
                            <Icon name='underline' />
                        </Button>
                        <Button icon toggle active={active === 'ITALIC'} onClick={() => buttonCommand('ITALIC')}>
                            <Icon name='italic' />
                        </Button>
                    </Button.Group>
                </>
            }
            <EditorContainer editNote={editNote}>
                <Editor
                    ref={editor}
                    editorState={editorState}
                    onChange={onChange}
                    handleKeyCommand={handleKeyCommand}
                    plugins={plugins}
                    readOnly={!editNote}
                />
            </EditorContainer>
        </>
    )
}

export default NoteEditor