<template>
    <div class="container">
        <ul class="nav nav-tabs  mb-3">
            <li class="nav-item">
                <a :class="currentComponent == 'company-component' ? 'nav-link active' : 'nav-link'"
                    href="javascript:void(0);" @click="swapComponent('company-component')">Company</a>
            </li>
            <li class="nav-item">
                <a :class="currentComponent == 'employee-component' ? 'nav-link active' : 'nav-link'"
                    href="javascript:void(0);" @click="swapComponent('employee-component')">Employee</a>
            </li>
        </ul>
        <div :is="currentComponent"></div>
    </div>
</template>
<script>
export default {
    name: "Maincontent",
    data() {
        return {
            currentComponent: 'company-component', // default company-component
            componentsArray: ['company-component', 'employee-component']
        }
    },
    components: {
        'company-component': {
            template: '<company-component/>'
        },
        'employee-component': {
            template: '<employee-component/>'
        }
    },
    methods: {
        swapComponent: function (component) {
            this.currentComponent = component;
            // console.log(this);
        }
    }
    ,
    mounted() {
        // console.log('Header Component mounted.-')
    }
}
</script>
