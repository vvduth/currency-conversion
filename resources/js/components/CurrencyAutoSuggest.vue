<template>
    <div class="relative">
        <label v-if="label" class="block text-gray-700 font-semibold mb-2">{{ label }}</label>
        <input type="text" v-model="query" @focus="showSuggestions = true" @blur="hideSuggestions"
            placeholder="Type currency code/name" class="border p-2 w-full" />
        <ul v-if="showSuggestions && filteredCurrencies.length"
            class="absolute bg-white border border-gray-300 w-full mt-1 max-h-60 overflow-y-auto z-10">
            <li v-for="currency in filteredCurrencies" :key="currency.code" @click="selectCurrency(currency)"
                class="p-2 hover:bg-gray-200 cursor-pointer">
                {{ currency.name }} ({{ currency.code }})
            </li>
            <li v-if="!filteredCurrencies.length" class="p-2 text-gray-500">
                No suggestions found
            </li>
            <li v-else class="p-2 text-gray-500">
                {{ filteredCurrencies.length }} suggestions found
                ></li>
        </ul>
        <p v-if="query && !filteredCurrencies.length" class="text-red-500 mt-2">
            No matching currencies found, make sure to check the spelling.
        </p>
        <p v-if="label === 'From' && query !== 'EUR'">
            <span class="text-red-500 text-xs">Note: The service only works if this field is EUR.</span>
        </p>

    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
const props = defineProps({
    modelValue: String,
    label: String,
    currencies: {
        type: Array,
    }
});
const emit = defineEmits(['update:modelValue']);
const query = ref(props.modelValue || '');
const showSuggestions = ref(false);
const filteredCurrencies = computed(() => {
    if (!query.value) return [];
    return props.currencies.filter(currency =>
        currency.name.toLowerCase().includes(query.value.toLowerCase()) ||
        currency.code.toLowerCase().includes(query.value.toLowerCase())
    );
});

function selectCurrency(currency) {
    query.value = currency.code;
    emit('update:modelValue', currency.code);
    showSuggestions.value = false;
}

function hideSuggestions() {
    setTimeout(() => {
        showSuggestions.value = false;
    }, 200);
}

watch(query, (newValue) => {
    emit('update:modelValue', newValue);
})

</script>
