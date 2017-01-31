// Default settings for network
var networkOptions = {
    layout: {
        hierarchical: {
            sortMethod: 'directed'
        }
    },
    nodes: {
        font: "20px arial black",
        shadow: {
            enabled: true
        },
        color: {
            highlight: {
                background: "#46FF2E",
                border: "#BF58B0"
            }
        },
        shape: "box",
        labelHighlightBold: false,
        borderWidthSelected: 4
    },
    edges: {
        color: {
            color: "#555",
            highlight: "#555"
        },
        arrows: {
            to: {
                enabled: true
            }
        },
        hoverWidth: 0,
        selectionWidth: 0
    },
    interaction: {
        dragNodes: false,
        dragView: false,
        zoomView: false,
        hover: false,
        hoverConnectedEdges: false,
        selectConnectedEdges: false
    },
    physics: {
        enabled: true,
        hierarchicalRepulsion: {
            nodeDistance: 165
        },
        solver: 'hierarchicalRepulsion'
    }
};