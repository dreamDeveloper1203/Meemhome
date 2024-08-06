import { IProduct } from "./product.interface";

export interface ICategory {
    id: string,
    name: string,
    image_url: string,
    sort_order: number | null,
    items: IProduct[],
}