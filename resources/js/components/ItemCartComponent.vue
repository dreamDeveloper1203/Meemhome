<template>
  <div class="fw-bolder h2 pb-1 d-flex align-items-center " v-if="item.base_price">
    <span :class="item.has_discount ? 'text-danger' : ''"> {{ price }}</span>
    <s class="mx-2" v-if="item.has_discount">{{ item.view_original_price }}</s>
    <span v-if="item.has_discount" class="badge text-bg-danger user-select-none fs-5">Sale</span>
  </div>

  <div v-if="item?.colors.length" class="py-2 fs-3 d-flex align-items-center">
    <label for="color">Color:</label>
    <select name="color" id="" v-model="selectedColor" @change="handleProductColor">
      <option disabled value="">Please select a color</option>
      <option v-for="color in item.colors" :key="color" :value="color">{{ color }}</option>
    </select>
  </div>
  <div v-if="item?.sizes.length" class="py-2 fs-3 d-flex align-items-center">
    <label for="size">Size:</label>
    <select name="size" id="" v-model="selectedSize" @change="handleProductSize">
      <option disabled value="">Please select a size</option>
      <option v-for="size in item.sizes" :key="size" :value="size">{{ size }}</option>
    </select>
  </div>

  <div class="py-2 fs-3">
    In Stock: {{ item.in_stock }}
  </div>
  <div class="row border-bottom border-top py-2">
    <div class="col-md-6 d-flex align-items-center mb-3 mb-md-0 justify-content-md-start justify-content-center">
      <div class="d-flex align-items-center">
        <div>
          <button type="button"
            class="btn btn-sm btn-primary shadow-none rounded-circle d-flex align-items-center justify-content-center"
            @click="decrement()" style="width: 40px; height: 40px">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="hero-icon">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
            </svg>
          </button>
        </div>
        <div class="h4 px-5 text-center m-0 fw-bold">
          {{ quantity }}
        </div>
        <div>
          <button type="button"
            class="btn btn-sm btn-primary shadow-none rounded-circle d-flex align-items-center justify-content-center"
            @click="increment()" style="width: 40px; height: 40px">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="hero-icon">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
          </button>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <button class="btn btn-danger w-100 text-uppercase" type="button" :disabled="!isAddToCartEnabled"
        @click="addToCart()">Add to cart</button>
    </div>
  </div>
</template>

<script>
import { usd_money_format } from '@/services/utils';
import { ShoppingCart } from '@/services/shopping-cart';
export default {
  props: ['item'],
  data() {
    return {
      quantity: 1,
      selectedColor: '',
      selectedSize: ''
    };
  },
  created() { },
  methods: {
    addToCart() {
      try {
        const currentCartQuantity = ShoppingCart.getProductCount(this.item);
        if (this.quantity + currentCartQuantity > Number(this.item.in_stock)) {
          this.$toast.show('Not enough stock available');
          return;
        }
        ShoppingCart.add(this.item, this.quantity);
        console.log("item:", this.item)
        this.$store.state.cartTotal = ShoppingCart.total();
        this.$store.state.cartTotalItems = ShoppingCart.totalItems();
        this.$toast.show('Added to your cart');
      } catch (ex) {
        console.log(ex.message);
      }
    },
    increment() {
      if (this.item.is_offer) {
        if (this.quantity >= Number(this.item.in_stock)) {
          return;
        }

      } else {
        if (this.quantity >= 100) return;
      }
      this.quantity++;
    },

    decrement() {
      if (this.quantity < 2) return;
      this.quantity--;
    },
    formatNumber(number) {
      return usd_money_format(number, this.currency);
    },

    /**
     * Funciton to set query product color
     */
    handleProductColor() {
      if ('URLSearchParams' in window) {
        var searchParams = new URLSearchParams(window.location.search);
        searchParams.set('color', `${this.selectedColor}`);
        window.location.search = searchParams.toString();
      }
    },
    handleProductSize() {
      if ('URLSearchParams' in window) {
        var searchParams = new URLSearchParams(window.location.search);
        searchParams.set('size', `${this.selectedSize}`);
        window.location.search = searchParams.toString();
      }
    },
    getQueryParam(param) {
      const urlParams = new URLSearchParams(window.location.search);
      return urlParams.get(param);
    }
  },
  mounted() {
    this.selectedColor = this.getQueryParam('color') || '';
    this.selectedSize = this.getQueryParam('size') || '';
  },
  computed: {
    price() {
      return usd_money_format(parseFloat(this.item.base_price) * parseInt(this.quantity));
    },
    isAddToCartEnabled() {
      return (this.item.sizes.length === 0 || this.selectedSize !== '') &&
        (this.item.colors.length === 0 || this.selectedColor !== '');
    }
  }
};
</script>
