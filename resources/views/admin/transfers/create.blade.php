            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Número de Pessoas</label>
                <input type="number" name="num_pessoas" class="form-input w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" min="1" max="50" value="{{ old('num_pessoas') }}" required>
                @error('num_pessoas')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                <textarea name="observacoes" class="form-input w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" rows="3" placeholder="Informações adicionais...">{{ old('observacoes') }}</textarea>
                @error('observacoes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div> 