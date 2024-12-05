/**
 * Gutenberg Blocks
 *
 * All blocks related JavaScript files should be imported here.
 * You can create a new block folder in this dir and include code
 * for that block here as well.
 *
 * All blocks should be included here since this is the file that
 * Webpack is compiling as the input file.
 */

import { EcwidIcons } from './includes/icons.js';
wp.blocks.updateCategory('ec-store', { icon: EcwidIcons.ecwid });

import './block/block.js';
import './product/block.js';
import './buynow/block.js';
import './search/block.js';
import './categories/block.js';
import './minicart/block.js';
import './category-page/block.js';
import './product-page/block.js';
import './filters-page/block.js';
import './cart-page/block.js';