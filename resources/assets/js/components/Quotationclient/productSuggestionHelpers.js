function normalizeSuggestionValue(value) {
    let text = (value || '').toString().trim()

    if (text === '') {
        return ''
    }

    try {
        text = text.normalize('NFD').replace(/[\u0300-\u036f]/g, '')
    } catch (error) {
        // Ignore normalization issues and continue with the raw string.
    }

    return text
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, ' ')
        .replace(/\s+/g, ' ')
        .trim()
}

function createProductOptionSuggestion(product) {
    return {
        product_id: product.value || null,
        product_name: product.label || '',
        product_code: product.codebar || '',
        product_key: normalizeSuggestionValue(product.label || ''),
        display_label: product.codebar ? `${product.label} | ${product.codebar}` : (product.label || ''),
        source_type: 'product'
    }
}

function createCatalogSuggestion(suggestion) {
    return {
        ...suggestion,
        product_id: null,
        product_name: suggestion.product_name || '',
        product_code: suggestion.product_code || '',
        product_key: suggestion.product_key || normalizeSuggestionValue(suggestion.product_name || ''),
        display_label: suggestion.display_label || (
            suggestion.categoria
                ? `${suggestion.product_name} | ${suggestion.categoria}`
                : (suggestion.product_name || '')
        ),
        source_type: suggestion.source_type || 'catalog'
    }
}

function shouldIncludeSuggestion(suggestion, normalizedTerm) {
    if (normalizedTerm === '') {
        return true
    }

    const haystack = normalizeSuggestionValue([
        suggestion.product_name,
        suggestion.product_code,
        suggestion.display_label,
        suggestion.categoria
    ].filter(Boolean).join(' '))

    return haystack.includes(normalizedTerm)
}

function appendSuggestions(target, seenKeys, suggestions, normalizedTerm) {
    suggestions.forEach((suggestion) => {
        const productName = (suggestion.product_name || '').trim()
        const productKey = suggestion.product_key || normalizeSuggestionValue(productName)

        if (productName === '' || productKey === '') {
            return
        }

        if (seenKeys.has(productKey)) {
            return
        }

        if (!shouldIncludeSuggestion(suggestion, normalizedTerm)) {
            return
        }

        target.push({
            ...suggestion,
            product_name: productName,
            product_key: productKey
        })

        seenKeys.add(productKey)
    })
}

export function buildCombinedProductSuggestions({
    modelSuggestions = [],
    productOptions = [],
    catalogSuggestions = [],
    term = '',
    limit = 20
} = {}) {
    const normalizedTerm = normalizeSuggestionValue(term)
    const combinedSuggestions = []
    const seenKeys = new Set()

    appendSuggestions(combinedSuggestions, seenKeys, modelSuggestions, normalizedTerm)
    appendSuggestions(
        combinedSuggestions,
        seenKeys,
        productOptions.map(createProductOptionSuggestion),
        normalizedTerm
    )
    appendSuggestions(
        combinedSuggestions,
        seenKeys,
        catalogSuggestions.map(createCatalogSuggestion),
        normalizedTerm
    )

    return combinedSuggestions.slice(0, limit)
}
