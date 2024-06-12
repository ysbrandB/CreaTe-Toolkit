<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
const props = defineProps<{
    json_items: any;
}>();

console.log(props.json_items);
import { VNetworkGraph } from "v-network-graph"
import "v-network-graph/lib/style.css"
import 'd3-force';
import { reactive, ref, watch } from "vue"
import * as vNG from "v-network-graph"
import {
    ForceLayout,
    ForceNodeDatum,
    ForceEdgeDatum,
} from "v-network-graph/lib/force-layout"

const nodeCount = ref(20)
const nodes = reactive({})
const edges = reactive({})

// initialize network
buildNetwork(nodeCount.value, nodes, edges)

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
            label: {
                visible: false,
            },
        },
    })
)

function buildNetwork(count: number, nodes: vNG.Nodes, edges: vNG.Edges) {
    const idNums = [...Array(count)].map((_, i) => i)

    // nodes
    const newNodes = Object.fromEntries(idNums.map(id => [`node${id}`, {}]))
    Object.keys(nodes).forEach(id => delete nodes[id])
    Object.assign(nodes, newNodes)

    // edges
    const makeEdgeEntry = (id1: number, id2: number) => {
        return [`edge${id1}-${id2}`, { source: `node${id1}`, target: `node${id2}` }]
    }
    const newEdges = Object.fromEntries([
        ...idNums
            .map(n => [n, (Math.floor(n / 4) * 4) % count])
            .map(([n, m]) => (n === m ? [n, (n + 4) % count] : [n, m]))
            .map(([n, m]) => makeEdgeEntry(n, m)),
    ])
    Object.keys(edges).forEach(id => delete edges[id])
    Object.assign(edges, newEdges)
}
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>
        <div class="demo-control-panel">
            <label>Node count:</label>
            <el-input-number v-model="nodeCount" :min="3" :max="200" />
            <label>(&lt;= 200)</label>
        </div>

        <v-network-graph
            class="h-screen w-full"
            :zoom-level="0.5"
            :nodes="nodes"
            :edges="edges"
            :configs="configs"
        />
    </AuthenticatedLayout>
</template>
