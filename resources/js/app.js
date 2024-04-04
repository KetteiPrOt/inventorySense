import './bootstrap';
import mask from '@alpinejs/mask';
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

Alpine.plugin(mask);
 
Livewire.start();
