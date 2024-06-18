<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import {Configs, Edges, VNetworkGraph} from "v-network-graph"
import "v-network-graph/lib/style.css"
import 'd3-force';
import {reactive, ref, watch} from "vue"
import * as vNG from "v-network-graph"
import {
    ForceLayout,
    ForceNodeDatum,
    ForceEdgeDatum,
} from "v-network-graph/lib/force-layout"
import Card from "@/Components/Card.vue";
import {Item} from "@/types";
import SelectedItemDropdown from "@/CustomComponents/SelectedItemDropdown.vue";
import axios from "axios";
import PrimaryButton from "@/Components/PrimaryButton.vue";

interface Node extends vNG.Node {
    size?: number
    color?: string
    label?: boolean
}

interface Edge extends vNG.Edge {
    width?: number
    color?: string
    dashed?: boolean
}

const props = defineProps<{
    items: Item[],
    nodes: Item[]
    python: Item,
    initialSelectedItems?: Item[],
}>();

const selectedItems = ref(new Set<Item>(props.initialSelectedItems ?? []));
const myEdges: Set<Edge> = new Set<Edge>();
const nodes = reactive({})
const edges = reactive({})
if (props.python) {
    //@ts-ignore
    nodes[`node${props.python.id}`] = {
        name: props.python.title,
        photo_url: props.python.photo_url,
        public_id: props.python.public_id
    }
}

const addNode = (item: Item) => {
    const node: Node = {name: item.title, photo_url: item.photo_url, public_id: item.public_id};
    //@ts-ignore
    nodes[`node${item.id}`] = node;
}

props.items.forEach((item: Item) => {
    addNode(item)
    const list = [item.id, ...item.json_items ?? [], props.python.id]
    for (let i = 0; i < list.length - 1; i++) {
        //@ts-ignore
        myEdges.add({source: `node${list[i]}`, target: `node${list[i + 1]}`})
    }
})

props.nodes.forEach((item: Item) => {
    addNode(item)
})

//filter out the myEdges where source is target and target is source from another item
Array.from(myEdges).forEach((edge: Edge, index: number) => {
    if (!myEdges.has({source: edge.target, target: edge.source})) {
        //@ts-ignore
        edges[`edge${index}`] = edge
    }
})

const eventHandlers: vNG.EventHandlers = {
    "node:click": ({node}) => {
        // toggle
        router.get(route('items.show',
            // @ts-ignore
            {public_id: nodes[node].public_id}))
    },
}

const configs = reactive(
    vNG.defineConfigs({
        view: {
            layoutHandler: new ForceLayout({
                positionFixedByDrag: false,
                positionFixedByClickWithAltKey: true,
                createSimulation: (d3, nodes, edges) => {
                    // d3-force parameters
                    //@ts-ignore
                    const forceLink = d3.forceLink<ForceNodeDatum, ForceEdgeDatum>(edges).id(d => d.id)
                    return d3
                        .forceSimulation(nodes)
                        .force("edge", forceLink.distance(40).strength(0.5))
                        .force("charge", d3.forceManyBody().strength(-800))
                        .force("center", d3.forceCenter().strength(0.05))
                        .alphaMin(0.001)
                }
            }),
        },
        node: {
            selectable: false,
            normal: {
                radius: 20,
            },
            label: {
                visible: true,
                fontFamily: undefined,
                fontSize: 11,
                lineHeight: 1.1,
                color: "#000000",
                margin: 4,
                direction: "south",
                text: "name",
            },
        },
        edge: {
            summarize: ((edges: Edges, configs: Configs) => true),
            summarized: { // configs for summarized edge
                label: {
                    // * These fields can also be specified with the function as
                    //   `(edges: Record<string, Edge>) => value`.
                    fontSize: 0, // font size.  default: 10
                    color: "#4466cc"      // font color. default: "#4466cc"
                },
                shape: {
                    width: 0,
                    height: 0,
                },
                stroke: {
                    width: 2,
                }
            }
        },
    })
)


watch(selectedItems.value, (_) => {
    router.reload();
})
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="w-full flex flex-row justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Item Overview</h2>
                <selected-item-dropdown :selected-items="selectedItems">
                    <template #empty>
                        <p> Narrow the graph down by selecting some items in the item overview!</p>
                    </template>
                    <template #button>
                        <primary-button
                            class="mt-2 w-fit self-end"
                            @click="router.get(route('items.index'))">
                            Select some items!
                        </primary-button>
                    </template>
                </selected-item-dropdown>
            </div>
        </template>
        <card class="max-w-6xl mx-auto h-full">
            <v-network-graph
                class="w-full h-[70vh]"
                :nodes="nodes"
                :edges="edges"
                :configs="configs"
                :event-handlers="eventHandlers"
            >
                <defs>
                    <!--
                      Define the path for clipping the face image.
                      To change the size of the applied node as it changes,
                      add the `clipPathUnits="objectBoundingBox"` attribute
                      and specify the relative size (0.0~1.0).
                    -->
                    <clipPath id="faceCircle" clipPathUnits="objectBoundingBox">
                        <circle cx="0.5" cy="0.5" r="0.5"/>
                    </clipPath>
                </defs>

                <!-- Replace the node component -->
                <template #override-node="{ nodeId, scale, config, ...slotProps }">
                    <!-- circle for filling background -->
                    <circle
                        class="face-circle"
                        :r="config.radius * scale"
                        fill="#ffffff"
                        v-bind="slotProps"
                    />
                    <image
                        class="face-picture"
                        :x="-config.radius * scale"
                        :y="-config.radius * scale"
                        :width="config.radius * scale * 2"
                        :height="config.radius * scale * 2"
                        :xlink:href="
                        //@ts-ignore
                        `${nodes[nodeId].photo_url}`"
                        clip-path="url(#faceCircle)"
                    />
                    <!-- circle for drawing stroke -->
                    <circle
                        class="face-circle"
                        :r="config.radius * scale"
                        fill="none"
                        stroke="#808080"
                        :stroke-width="1 * scale"
                        v-bind="slotProps"
                    />
                </template>
            </v-network-graph>
        </card>
    </AuthenticatedLayout>
</template>

<style lang="css" scoped>
.face-circle,
.face-picture {
    transition: all 0.1s linear;
}

.face-picture {
    object-fit: cover;
    pointer-events: none;
}
</style>
