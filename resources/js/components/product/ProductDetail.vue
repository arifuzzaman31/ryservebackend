
<template>
   <div id="ProductDetails" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
        <div class="modal-dialog modal-xl cusotom-modal">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Product View</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body" v-if="product">
                    <div class="row d-flex justify-content-between">
                        <div class="col-md-5 col-12">
                            <ul class="list-group ">
                                <li  class="list-group-item"><b>Product Name : {{ product.product_name }}</b></li>
                                <li  class="list-group-item"><b>Category : {{ product.category.category_name }}</b></li>
                                <li  class="list-group-item"><b>Sub-Category : {{ product.subcategory.category_name }}</b></li>
                                <li  class="list-group-item"><b>Design Code : {{ product.design_code }}</b></li>
                                <li  class="list-group-item"><b>Tax : {{ product.vat.tax_percentage }}%</b></li>
                                <li  class="list-group-item"><b>L: {{ product.length }} H:{{ product.height }} W:{{ product.width }} </b></li>
                                <li  class="list-group-item"><b>Weight : {{ product.weight }}</b></li>
                                <li  class="list-group-item"><b>Lead Time : {{ product.lead_time }}</b></li>
                                <li  class="list-group-item"><b>Fragile : {{ product.fragile }}</b></li>
                                <li  class="list-group-item"><b>Fragile Charge : {{ product.fragile_charge }}</b></li>
                            </ul>

                        </div>
                        <div class="d-flex justify-content-around col-md-7 col-12 w-100">
                            <v-lazy-image v-if="product.image_one" :src="product.image_one" class="img-fluid orderImage" alt="product-image" :src-placeholder="url+'demo.png'" />
                            <v-lazy-image v-if="product.image_two" :src="product.image_two" class="img-fluid orderImage" alt="product-image" :src-placeholder="url+'demo.png'" />
                            <v-lazy-image v-if="product.image_three" :src="product.image_three" class="img-fluid orderImage" alt="product-image" :src-placeholder="url+'demo.png'" />
                            <v-lazy-image v-if="product.image_four" :src="product.image_four" class="img-fluid orderImage" alt="product-image" :src-placeholder="url+'demo.png'" />
                            <v-lazy-image v-if="product.image_five" :src="product.image_five" class="img-fluid orderImage" alt="product-image" :src-placeholder="url+'demo.png'" />
                        </div>
                    </div>

                    <div class="row mt-2 ">
                        <div class="col-12">
                            <ul class="list-group ">
                                <li v-if="product.product_fabric && product.product_fabric.length > 0" class="list-group-item"><p><b>Fabric : </b><span v-for="pf in product.product_fabric" :key="pf.id">{{ pf.fabric_name }},</span></p></li>
                                <li v-if="product.product_vendor && product.product_vendor.length > 0" class="list-group-item"><p><b>Vendor : </b><span v-for="pbr in product.product_vendor" :key="pbr.id">{{ pbr.vendor_name }},</span></p></li>
                                <li v-if="product.product_brand && product.product_brand.length > 0" class="list-group-item"><p><b>Brand : </b><span v-for="br in product.product_brand" :key="br.id">{{ br.brand_name }},</span></p></li>
                                <li v-if="product.product_designer && product.product_designer.length > 0" class="list-group-item"><p><b>Designer : </b><span v-for="pde in product.product_designer" :key="pde.id">{{ pde.designer_name }},</span></p></li>
                                <li v-if="product.product_embellishment && product.product_embellishment.length > 0" class="list-group-item"><p><b>Embellishment : </b><span v-for="emb in product.product_embellishment" :key="emb.id">{{ emb.embellishment_name }},</span></p></li>
                                <li v-if="product.product_making && product.product_making.length > 0" class="list-group-item"><p><b>Making : </b><span v-for="mk in product.product_making" :key="mk.id">{{ mk.making_name }},</span></p></li>
                                <li v-if="product.product_season && product.product_season.length > 0" class="list-group-item"><p><b>Season : </b><span v-for="sea in product.product_season" :key="sea.id">{{ sea.season_name }},</span></p></li>
                                <li v-if="product.product_variety && product.product_variety.length > 0" class="list-group-item"><p><b>Variety : </b><span v-for="vari in product.product_variety" :key="vari.id">{{ vari.variety_name }},</span></p></li>
                                <li v-if="product.product_artist && product.product_artist.length > 0" class="list-group-item"><p><b>Artist : </b><span v-for="art in product.product_artist" :key="art.id">{{ art.artist_name }},</span></p></li>
                                <li v-if="product.product_consignment && product.product_consignment.length > 0" class="list-group-item"><p><b>Consignment : </b><span v-for="consig in product.product_consignment" :key="consig.id">{{ consig.consignment }},</span></p></li>
                                <li v-if="product.product_ingredient && product.product_ingredient.length > 0" class="list-group-item"><p><b>Ingredient : </b><span v-for="ingrad in product.product_ingredient" :key="ingrad.id">{{ ingrad.ingredient_name }},</span></p></li>
                                <li v-if="product.product_care && product.product_care.length > 0" class="list-group-item"><p><b>Care : </b><span v-for="car in product.product_care" :key="car.id">{{ car.care_name }},</span></p></li>
                                <li  class="list-group-item"><p><b>Colour : </b>{{ product.flat_colour }}</p></li>
                            </ul>
                        </div>
                    </div>

                    <div class="row mt-2 ">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center table-hover table-striped table-checkable table-highlight-head mb-4">
                                    <thead>
                                        <tr>
                                            <th>Colour</th>
                                            <th>Size</th>
                                            <th>SKU</th>
                                            <th>CPU</th>
                                            <th>MRP</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="(attr) in product.inventory" :key="attr.id" >
                                            <tr>
                                                <td>
                                                    <p class="mb-0">{{ attr.colour && attr.colour.color_name != '' ? attr.colour.color_name : 'N/A' }}</p>
                                                </td>
                                                <td>
                                                    <p class="mb-0">{{ attr.size && attr.size.size_name != '' ? attr.size.size_name : 'N/A' }}</p>
                                                </td>
                                                <td>{{ attr.sku }}</td>
                                                <td>{{ formatPrice(attr.cpu) }}</td>
                                                <td>{{ formatPrice(attr.mrp) }}</td>
                                                <td>
                                                    {{ attr.stock }}
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Mixin from '../../mixer'
export default {
props: ['product'],
mixins: [Mixin],
data(){
    return {
        url: baseUrl
    }
}
}
</script>

<style scoped>
.cusotom-modal {
    width: 100% !important;
}

.orderImage{
max-height: 40%;
max-width: 30%;
}

</style>
