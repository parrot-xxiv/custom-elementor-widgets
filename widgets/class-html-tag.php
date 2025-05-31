<?php

/**
 * HTML Tag Elementor Widget for Custom Elementor Widgets plugin.
 *
 * @package Custom_Elementor_Widgets
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class HTML_Tag_Widget extends \Elementor\Widget_Base
{

	public function get_name()
	{
		return 'html_tag_widget';
	}

	public function get_title()
	{
		return __('HTML Tag', 'custom-elementor-widgets');
	}

	public function get_icon()
	{
		return 'eicon-heading';
	}

	public function get_categories()
	{
		return ['basic'];
	}

	protected function register_controls()
	{
		// Content Section
		$this->start_controls_section(
			'content_section',
			[
				'label' => __('Content', 'custom-elementor-widgets'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'html_tag',
			[
				'label' => __('HTML Tag', 'custom-elementor-widgets'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
			]
		);

		$this->add_control(
			'text',
			[
				'label' => __('Text', 'custom-elementor-widgets'),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __('Your text here', 'custom-elementor-widgets'),
				'placeholder' => __('Enter your text', 'custom-elementor-widgets'),
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __('Link', 'custom-elementor-widgets'),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __('https://your-link.com', 'custom-elementor-widgets'),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __('Alignment', 'custom-elementor-widgets'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'custom-elementor-widgets'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'custom-elementor-widgets'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'custom-elementor-widgets'),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __('Justified', 'custom-elementor-widgets'),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Animation Section
		$this->start_controls_section(
			'animation_section',
			[
				'label' => __('Entrance Animation', 'custom-elementor-widgets'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'entrance_animation',
			[
				'label' => __('Animation Type', 'custom-elementor-widgets'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => __('None', 'custom-elementor-widgets'),
					'staggered_blur_fade' => __('Staggered Blur & Fade-In Letters', 'custom-elementor-widgets'),
					'typewriter' => __('Typewriter Effect with Blinking Cursor', 'custom-elementor-widgets'),
				],
			]
		);

		$this->add_control(
			'animation_duration',
			[
				'label' => __('Animation Duration (ms)', 'custom-elementor-widgets'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 2000,
				],
				'range' => [
					'px' => [
						'min' => 500,
						'max' => 10000,
						'step' => 100,
					],
				],
				'condition' => [
					'entrance_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'animation_delay',
			[
				'label' => __('Animation Delay (ms)', 'custom-elementor-widgets'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5000,
						'step' => 100,
					],
				],
				'condition' => [
					'entrance_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'stagger_delay',
			[
				'label' => __('Letter Stagger Delay (ms)', 'custom-elementor-widgets'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 50,
				],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
						'step' => 10,
					],
				],
				'condition' => [
					'entrance_animation' => 'staggered_blur_fade',
				],
			]
		);

		$this->add_control(
			'typewriter_speed',
			[
				'label' => __('Typing Speed (ms per character)', 'custom-elementor-widgets'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 100,
				],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 300,
						'step' => 10,
					],
				],
				'condition' => [
					'entrance_animation' => 'typewriter',
				],
			]
		);

		$this->add_control(
			'cursor_blink_speed',
			[
				'label' => __('Cursor Blink Speed (ms)', 'custom-elementor-widgets'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 500,
				],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 2000,
						'step' => 100,
					],
				],
				'condition' => [
					'entrance_animation' => 'typewriter',
				],
			]
		);

		$this->end_controls_section();

		// Style Section
		$this->start_controls_section(
			'style_section',
			[
				'label' => __('Style', 'custom-elementor-widgets'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'label' => __('Typography', 'custom-elementor-widgets'),
				'selector' => '{{WRAPPER}} .html-tag-element',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __('Text Color', 'custom-elementor-widgets'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .html-tag-element' => 'color: {{VALUE}};',
					'{{WRAPPER}} .html-tag-element a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'label' => __('Text Shadow', 'custom-elementor-widgets'),
				'selector' => '{{WRAPPER}} .html-tag-element',
			]
		);

		$this->add_responsive_control(
			'margin',
			[
				'label' => __('Margin', 'custom-elementor-widgets'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .html-tag-element' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => __('Padding', 'custom-elementor-widgets'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .html-tag-element' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$tag = $settings['html_tag'];
		$text = $settings['text'];
		$animation = $settings['entrance_animation'];

		$this->add_render_attribute('html_tag', 'class', 'html-tag-element');

		// Add animation classes and data attributes
		if ($animation !== 'none') {
			$this->add_render_attribute('html_tag', 'class', 'has-entrance-animation');
			$this->add_render_attribute('html_tag', 'class', 'animation-' . $animation);
			$this->add_render_attribute('html_tag', 'data-animation', $animation);

			// Safe array access with fallback values
			$duration = isset($settings['animation_duration']['size']) ? $settings['animation_duration']['size'] : 2000;
			$delay = isset($settings['animation_delay']['size']) ? $settings['animation_delay']['size'] : 0;

			$this->add_render_attribute('html_tag', 'data-duration', $duration);
			$this->add_render_attribute('html_tag', 'data-delay', $delay);

			if ($animation === 'staggered_blur_fade') {
				$stagger_delay = isset($settings['stagger_delay']['size']) ? $settings['stagger_delay']['size'] : 50;
				$this->add_render_attribute('html_tag', 'data-stagger-delay', $stagger_delay);
			}

			if ($animation === 'typewriter') {
				$typing_speed = isset($settings['typewriter_speed']['size']) ? $settings['typewriter_speed']['size'] : 100;
				$cursor_blink = isset($settings['cursor_blink_speed']['size']) ? $settings['cursor_blink_speed']['size'] : 500;
				$this->add_render_attribute('html_tag', 'data-typing-speed', $typing_speed);
				$this->add_render_attribute('html_tag', 'data-cursor-blink', $cursor_blink);
			}
		}

		// Render the element
		if (! empty($settings['link']['url'])) {
			$this->add_link_attributes('link', $settings['link']);
			echo '<' . esc_attr($tag) . ' ' . $this->get_render_attribute_string('html_tag') . '>';
			echo '<a ' . $this->get_render_attribute_string('link') . '>' . esc_html($text) . '</a>';
			echo '</' . esc_attr($tag) . '>';
		} else {
			echo '<' . esc_attr($tag) . ' ' . $this->get_render_attribute_string('html_tag') . '>';
			echo esc_html($text);
			echo '</' . esc_attr($tag) . '>';
		}

		// Add animation styles and scripts
		if ($animation !== 'none') {
			$this->render_animation_assets();
		}
	}

	/**
	 * Render animation CSS and JavaScript
	 */
	protected function render_animation_assets()
	{
?>
		<style>
			/* Base animation styles */
			.html-tag-element.has-entrance-animation {
				position: relative;
				/* Good for potential absolute positioning inside if needed */
			}

			/* Initial invisible state for animations that need it */
			.html-tag-element.animation-staggered_blur_fade,
			.html-tag-element.animation-typewriter {
				opacity: 0;
				/* Start hidden, JS will make it visible via .initialized */
			}

			/* Staggered Blur & Fade-In Letters */
			.html-tag-element.animation-staggered_blur_fade.initialized {
				opacity: 1;
			}

			.html-tag-element.animation-staggered_blur_fade .html-tag-word {
				display: inline-block;
				/* Crucial for word-level wrapping */
				/* white-space: nowrap; */
				/* Uncomment if letters within a single word should never break to a new line */
			}

			.html-tag-element.animation-staggered_blur_fade .html-tag-letter {
				display: inline-block;
				opacity: 0;
				filter: blur(10px);
				transform: translateY(20px);
				transition: opacity 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94),
					filter 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94),
					transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
				/* transition-delay is set by JavaScript for each letter */
			}

			.html-tag-element.animation-staggered_blur_fade .html-tag-letter.animate {
				opacity: 1;
				filter: blur(0px);
				transform: translateY(0);
			}

			/* Typewriter Effect */
			.html-tag-element.animation-typewriter {
				/* opacity: 0; already set above */
				overflow: hidden;
				/* Keeps content within bounds as it types */
				white-space: nowrap;
				/* Prevents text from wrapping to the next line during typing */
				display: block;
				/* Or 'inline-block'. 'block' makes it behave like a heading/paragraph for layout */
			}

			.html-tag-element.animation-typewriter.initialized {
				opacity: 1;
				/* Becomes visible once JS initializes it */
			}

			.html-tag-element.animation-typewriter .html-tag-typewriter-text {
				display: inline-block;
				/* To sit nicely next to the cursor */
				vertical-align: baseline;
			}

			.html-tag-element.animation-typewriter .html-tag-cursor {
				display: inline-block;
				background-color: currentColor;
				/* Uses the text color for the cursor */
				width: 2px;
				/* Adjust cursor thickness */
				margin-left: 2px;
				/* Space between last char and cursor */
				animation: html-tag-blink 1s infinite;
				vertical-align: baseline;
				/* Align cursor with the text */
				/* height: 1em; */
				/* Optional: if you want cursor height to match text general height */
			}

			@keyframes html-tag-blink {

				0%,
				50% {
					opacity: 1;
				}

				51%,
				100% {
					opacity: 0;
				}
			}
		</style>

		<script>
			(function() {
				'use strict';

				// Scoped to avoid conflicts with other widgets
				// Using a simpler unique ID method for the namespace, compatible with your original.
				const WIDGET_NAMESPACE = 'htmlTagWidget_<?php echo esc_js($this->get_id()); ?>';
				// Using widget ID makes it unique per widget instance if this script is output per widget.
				// If script is global, Date.now + random is fine too.

				document.addEventListener('DOMContentLoaded', function() {
					// Consider using Elementor's frontend hooks for better integration if issues arise in editor
					// Example: elementorFrontend.hooks.addAction('frontend/element_ready/' + WIDGET_NAMESPACE + '.default', callback);

					const animatedElements = document.querySelectorAll('.html-tag-element.has-entrance-animation');

					if (typeof IntersectionObserver === 'undefined') {
						// Fallback for browsers that don't support IntersectionObserver
						animatedElements.forEach(element => {
							const animation = element.dataset.animation;
							const delay = parseInt(element.dataset.delay) || 0;
							setTimeout(() => {
								if (animation === 'staggered_blur_fade') {
									window[WIDGET_NAMESPACE + '_initStaggeredBlurFade'](element);
								} else if (animation === 'typewriter') {
									window[WIDGET_NAMESPACE + '_initTypewriter'](element);
								}
							}, delay);
						});
						return;
					}

					const observer = new IntersectionObserver((entries) => {
						entries.forEach(entry => {
							if (entry.isIntersecting) {
								const element = entry.target;
								const animation = element.dataset.animation;
								const delay = parseInt(element.dataset.delay) || 0;

								setTimeout(() => {
									if (animation === 'staggered_blur_fade') {
										if (typeof window[WIDGET_NAMESPACE + '_initStaggeredBlurFade'] === 'function') {
											window[WIDGET_NAMESPACE + '_initStaggeredBlurFade'](element);
										}
									} else if (animation === 'typewriter') {
										if (typeof window[WIDGET_NAMESPACE + '_initTypewriter'] === 'function') {
											window[WIDGET_NAMESPACE + '_initTypewriter'](element);
										}
									}
								}, delay);

								observer.unobserve(element); // Animate only once
							}
						});
					}, {
						threshold: 0.1
					}); // Trigger when 10% of the element is visible

					animatedElements.forEach(el => observer.observe(el));
				});

				// Staggered Blur & Fade-In Letters Animation (Word Wrap Fixed)
				window[WIDGET_NAMESPACE + '_initStaggeredBlurFade'] = function(element) {
					const originalText = (element.textContent || element.innerText || '').trim();
					if (!originalText) {
						element.classList.add('initialized'); // Make visible even if empty
						return;
					}

					const staggerDelay = parseInt(element.dataset.staggerDelay) || 50;

					element.innerHTML = ''; // Clear current content
					element.classList.add('initialized'); // Make parent visible (opacity: 1)

					// Split by words and preserve spaces. (\s+) captures one or more whitespace characters.
					const parts = originalText.split(/(\s+)/);
					let currentDelay = 0;

					parts.forEach(part => {
						if (part.match(/^\s+$/)) { // It's one or more space characters
							element.appendChild(document.createTextNode(part)); // Append spaces as text node
						} else if (part.length > 0) { // It's a word
							const wordSpan = document.createElement('span');
							wordSpan.className = 'html-tag-word';
							for (let i = 0; i < part.length; i++) {
								const char = part[i];
								const letterSpan = document.createElement('span');
								letterSpan.className = 'html-tag-letter';
								letterSpan.textContent = char;
								letterSpan.style.transitionDelay = `${currentDelay}ms`;
								wordSpan.appendChild(letterSpan);
								currentDelay += staggerDelay;
							}
							element.appendChild(wordSpan);
						}
					});

					// Trigger the animation by adding 'animate' class after a short delay
					// This ensures styles are applied and then transitions start based on individual delays
					setTimeout(() => {
						const letters = element.querySelectorAll('.html-tag-letter');
						letters.forEach(letter => {
							letter.classList.add('animate');
						});
					}, 50); // Small delay for rendering
				};

				// Typewriter Effect Animation (Fixed)
				window[WIDGET_NAMESPACE + '_initTypewriter'] = function(element) {
					// IMPORTANT: Get the text BEFORE modifying innerHTML
					const originalFullText = (element.textContent || element.innerText || '').trim();

					const typingSpeed = parseInt(element.dataset.typingSpeed) || 100;
					const cursorBlinkSpeed = parseInt(element.dataset.cursorBlink) || 500;

					// Clear the element and set up the structure for the typewriter
					// Do this after getting originalFullText
					element.innerHTML = '<span class="html-tag-typewriter-text"></span><span class="html-tag-cursor" style="animation-duration: ' + cursorBlinkSpeed + 'ms;"></span>';
					element.classList.add('initialized'); // Make it visible

					if (!originalFullText) { // If there's no text, nothing to type.
						// Cursor will still be visible if not hidden. Could hide cursor here too.
						const cursorElement = element.querySelector('.html-tag-cursor');
						if (cursorElement) cursorElement.style.display = 'none';
						return;
					}

					const textElement = element.querySelector('.html-tag-typewriter-text');
					// const cursorElement = element.querySelector('.html-tag-cursor'); // For potential hiding later

					if (!textElement) {
						console.error('Typewriter text span not found within:', element);
						return;
					}

					let currentIndex = 0;
					let displayedText = '';

					function typeNextCharacter() {
						if (currentIndex < originalFullText.length) {
							const char = originalFullText[currentIndex];
							// For HTML, literal spaces can collapse. Using &nbsp; ensures they are preserved as typed.
							if (char === ' ') {
								displayedText += '&nbsp;';
							} else {
								displayedText += char;
							}
							textElement.innerHTML = displayedText; // Update the displayed text
							console.log(textElement.innerHTML)
							currentIndex++;
							setTimeout(typeNextCharacter, typingSpeed);
						} else {
							// Typing finished
							// Optional: hide cursor after typing if desired
							// if (cursorElement) {
							//     cursorElement.style.display = 'none'; 
							// }
						}
					}

					typeNextCharacter(); // Start the typing animation
				};

				// You can keep your extensible animation system placeholder
				window[WIDGET_NAMESPACE + '_initCustomAnimation'] = function(element, animationType) {
					// Future animations can be added here
				};
			})();
		</script>
<?php
	}
}
