document.addEventListener('DOMContentLoaded', function() {
    // Image preview functionality
    const mainImageInput = document.querySelector('input[name="main_image"]');
    const additionalImagesInput = document.querySelector('input[name="additional_images[]"]');
    const mainImagePreview = document.getElementById('main_image_preview');
    const additionalImagesPreview = document.getElementById('additional_images_preview');

    mainImageInput.addEventListener('change', function(e) {
        mainImagePreview.innerHTML = '';
        if (this.files && this.files[0]) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(this.files[0]);
            img.style.maxWidth = '200px';
            img.style.marginTop = '10px';
            mainImagePreview.appendChild(img);
        }
    });

    additionalImagesInput.addEventListener('change', function(e) {
        additionalImagesPreview.innerHTML = '';
        if (this.files) {
            Array.from(this.files).forEach(file => {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.maxWidth = '150px';
                img.style.marginRight = '10px';
                img.style.marginTop = '10px';
                additionalImagesPreview.appendChild(img);
            });
        }
    });

    // Existing variations functionality
    const generateVariationsBtn = document.getElementById('generate-variations');
    const variationsContainer = document.getElementById('variations-container');
    const attributeSelects = document.querySelectorAll('.attribute-select');

    generateVariationsBtn.addEventListener('click', function() {
        // Get selected attribute values
        let selectedAttributes = [];
        attributeSelects.forEach(select => {
            let selectedOptions = Array.from(select.selectedOptions);
            if (selectedOptions.length > 0) {
                selectedAttributes.push({
                    attributeId: select.dataset.attributeId,
                    values: selectedOptions.map(option => ({
                        id: option.value,
                        name: option.dataset.valueName
                    }))
                });
            }
        });

        // Generate combinations
        let combinations = generateCombinations(selectedAttributes);
        
        // Clear previous variations
        variationsContainer.innerHTML = '';

        // Create variation forms
        combinations.forEach((combination, index) => {
            let variationHtml = `
                <div class="variation-item border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Variation ${index + 1}</h5>
                        <button type="button" class="btn btn-danger btn-sm delete-variation">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <strong>Attributes:</strong> ${combination.map(attr => `${attr.value}`).join(' / ')}
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>SKU</label>
                            <input type="text" name="variations[${index}][sku]" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Stock</label>
                            <input type="number" name="variations[${index}][stock]" class="form-control" required min="0">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Price</label>
                            <input type="number" name="variations[${index}][price]" class="form-control" step="0.01" required min="0">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Sale Price</label>
                            <input type="number" name="variations[${index}][sale_price]" class="form-control" step="0.01" min="0">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Sale Start</label>
                            <input type="datetime-local" name="variations[${index}][sale_start]" class="form-control">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Sale End</label>
                            <input type="datetime-local" name="variations[${index}][sale_end]" class="form-control">
                        </div>
                    </div>
                    ${combination.map(attr => `
                        <input type="hidden" name="variations[${index}][attribute_values][]" value="${attr.id}">
                    `).join('')}
                </div>
            `;
            variationsContainer.insertAdjacentHTML('beforeend', variationHtml);
        });

        // Add event listeners for delete buttons
        document.querySelectorAll('.delete-variation').forEach(button => {
            button.addEventListener('click', function() {
                const variationItem = this.closest('.variation-item');
                variationItem.remove();
                reindexVariations();
            });
        });
    });

    // Function to reindex variations after deletion
    function reindexVariations() {
        const variations = document.querySelectorAll('.variation-item');
        variations.forEach((variation, index) => {
            // Update variation title
            variation.querySelector('h5').textContent = `Variation ${index + 1}`;
            
            // Update input names
            variation.querySelectorAll('input').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/variations\[\d+\]/, `variations[${index}]`));
                }
            });
        });
    }

    function generateCombinations(attributes) {
        if (attributes.length === 0) return [];
        
        let combinations = attributes[0].values.map(value => [{
            id: value.id,
            value: value.name
        }]);
        
        for (let i = 1; i < attributes.length; i++) {
            const temp = [];
            for (let combination of combinations) {
                for (let value of attributes[i].values) {
                    temp.push([...combination, {
                        id: value.id,
                        value: value.name
                    }]);
                }
            }
            combinations = temp;
        }
        
        return combinations;
    }

    
});