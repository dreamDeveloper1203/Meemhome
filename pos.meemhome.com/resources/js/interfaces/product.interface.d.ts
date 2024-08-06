export interface IProduct {
    id: string;
    name: string;
    image_url: string;
    search_name: string;
    wholesale_price: number | undefined;
    retail_price: number | undefined;
    super_dealer_price: number | undefined;
    cost: number | undefined;
    description: string;
    barcode: string;
    sku: string;
    code: string;
    url: string;
    in_stock: number;
    continue_selling_when_out_of_stock: boolean;
    track_stock: boolean;
    category_id: string;
}
