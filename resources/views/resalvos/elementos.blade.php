<section class="bg-blue-900 text-white py-16 px-6">
  <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg p-6 text-black">
    <h2 class="text-2xl font-bold mb-4">Que tipo de veículo pretende?</h2>

    <!-- Tipo de veículo -->
    <div class="flex gap-4 mb-4">
      <button class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-md">🚗 Carros</button>
      <button class="flex items-center gap-2 px-4 py-2 bg-gray-200 rounded-md">🚚 Comerciais</button>
    </div>

    <!-- Formulário -->
    <form class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
      <!-- Estação -->
      <div class="col-span-2">
        <label class="block text-sm font-medium mb-1">Estação de levantamento e devolução</label>
        <input type="text" placeholder="Cidade, morada, ponto de interesse"
          class="w-full border border-gray-300 rounded-md px-4 py-2" />
      </div>

      <!-- Checkbox -->
      <div class="flex items-center space-x-2 mb-4 md:mb-0">
        <input type="checkbox" class="h-4 w-4" id="same-station" />
        <label for="same-station" class="text-sm">Devolver na mesma estação</label>
      </div>

      <!-- Data e hora de levantamento -->
      <div>
        <label class="block text-sm font-medium mb-1">Data e Hora de levantamento</label>
        <div class="flex gap-2">
          <input type="date" class="flex-1 border border-gray-300 rounded-md px-2 py-2" />
          <input type="time" class="flex-1 border border-gray-300 rounded-md px-2 py-2" />
        </div>
      </div>

      <!-- Data e hora de devolução -->
      <div>
        <label class="block text-sm font-medium mb-1">Data e Hora de devolução</label>
        <div class="flex gap-2">
          <input type="date" class="flex-1 border border-gray-300 rounded-md px-2 py-2" />
          <input type="time" class="flex-1 border border-gray-300 rounded-md px-2 py-2" />
        </div>
      </div>

      <!-- Filtros adicionais -->
      <div class="md:col-span-3 flex flex-wrap items-center gap-4 mt-4">
        <select class="border border-gray-300 rounded-md px-4 py-2">
          <option>Tenho 26+</option>
          <option>Tenho menos de 26</option>
        </select>

        <select class="border border-gray-300 rounded-md px-4 py-2">
          <option>Resido em Angola</option>
          <option>Outro país</option>
        </select>

        <label class="flex items-center space-x-2">
          <input type="checkbox" class="h-4 w-4" />
          <span class="text-sm">Tenho uma <strong>tarifa contratada</strong></span>
        </label>

        <!-- Botão -->
        <button type="submit" class="ml-auto bg-yellow-400 text-black font-semibold px-6 py-2 rounded-md">
          Procurar
        </button>
      </div>
    </form>
  </div>
</section>