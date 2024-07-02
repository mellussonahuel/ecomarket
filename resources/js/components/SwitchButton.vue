<template>
  <label
    :class="className"
    tabindex="0"
    role="checkbox"
    @keydown.space.prevent="keyToggle"
  >
    <input
      type="checkbox"
      class="v-switch-input"
      :name="name"
      :checked="value"
      :disabled="disabled"
      tabindex="-1"
      @change.stop="toggle"
    />
    <div class="v-switch-core" :style="coreStyle">
      <div class="v-switch-button" :style="buttonStyle" />
    </div>
    <template v-if="labels">
      <span class="v-switch-label v-left" :style="labelStyle" v-if="toggled">
        <slot name="checked">
          <template>{{ labelChecked }}</template>
        </slot>
      </span>
      <span class="v-switch-label v-right" :style="labelStyle" v-else>
        <slot name="unchecked">
          <template>{{ labelUnchecked }}</template>
        </slot>
      </span>
    </template>
  </label>
</template>

<script>
const DEFAULT_COLOR_CHECKED = "#75c791";
const DEFAULT_COLOR_UNCHECKED = "#bfcbd9";
const DEFAULT_LABEL_CHECKED = "on";
const DEFAULT_LABEL_UNCHECKED = "off";
const DEFAULT_SWITCH_COLOR = "#fff";
export default {
  name: "SwitchButton",
  props: {
    value: {
      type: [Boolean, Number],
      default: false
    },
    name: {
      type: String
    },
    disabled: {
      type: Boolean,
      default: false
    },
    tag: {
      type: String
    },
    sync: {
      type: Boolean,
      default: false
    },
    speed: {
      type: Number,
      default: 300
    },
    color: {
      type: [String, Object]
    },
    switchColor: {
      type: [String, Object]
    },
    cssColors: {
      type: Boolean,
      default: false
    },
    labels: {
      type: [Boolean, Object],
      default: false,
      validator(value) {
        return typeof value === "object"
          ? value.checked || value.unchecked
          : typeof value === "boolean";
      }
    },
    height: {
      type: Number,
      default: 25
    },
    width: {
      type: Number,
      default: 50
    },
    margin: {
      type: Number,
      default: 3
    },
    fontSize: {
      type: Number
    }
  },
  computed: {
    className() {
      let { toggled, disabled } = this;
      return [
        "switch",
        {
          toggled,
          disabled
        }
      ];
    },
    coreStyle() {
      return {
        width: this.px(this.width),
        height: this.px(this.height),
        backgroundColor: this.cssColors
          ? null
          : this.disabled
          ? this.colorDisabled
          : this.colorCurrent,
        borderRadius: this.px(Math.round(this.height / 2))
      };
    },
    buttonRadius() {
      return this.height - this.margin * 2;
    },
    distance() {
      return this.px(this.width - this.height + this.margin);
    },
    buttonStyle() {
      const transition = `transform ${this.speed}ms`;
      const margin = this.px(this.margin);
      const transform = this.toggled
        ? this.translate(this.distance, margin)
        : this.translate(margin, margin);
      const background = this.switchColor ? this.switchColorCurrent : null;
      return {
        width: this.px(this.buttonRadius),
        height: this.px(this.buttonRadius),
        transition,
        transform,
        background
      };
    },
    labelStyle() {
      return {
        lineHeight: this.px(this.height),
        fontSize: this.fontSize ? this.px(this.fontSize) : null
      };
    },
    colorChecked() {
      let { color } = this;
      if (!this.isObject(color)) {
        return color || DEFAULT_COLOR_CHECKED;
      }
      return this.get(color, "checked", DEFAULT_COLOR_CHECKED);
    },
    colorUnchecked() {
      return this.get(this.color, "unchecked", DEFAULT_COLOR_UNCHECKED);
    },
    colorDisabled() {
      return this.get(this.color, "disabled", this.colorCurrent);
    },
    colorCurrent() {
      return this.toggled ? this.colorChecked : this.colorUnchecked;
    },
    labelChecked() {
      return this.get(this.labels, "checked", DEFAULT_LABEL_CHECKED);
    },
    labelUnchecked() {
      return this.get(this.labels, "unchecked", DEFAULT_LABEL_UNCHECKED);
    },
    switchColorChecked() {
      return this.get(this.switchColor, "checked", DEFAULT_SWITCH_COLOR);
    },
    switchColorUnchecked() {
      return this.get(this.switchColor, "unchecked", DEFAULT_SWITCH_COLOR);
    },
    switchColorCurrent() {
      let { switchColor } = this;
      if (!this.isObject(this.switchColor)) {
        return this.switchColor || DEFAULT_SWITCH_COLOR;
      }
      return this.toggled ? this.switchColorChecked : this.switchColorUnchecked;
    }
  },
  watch: {
    value(value) {
      if (this.sync) {
        this.toggled = !!value;
      }
    }
  },
  data() {
    return {
      toggled: !!this.value
    };
  },
  methods: {
    validate(value) {
      return (
        this.isString(value) ||
        this.has(value, "checked") ||
        this.has(value, "unchecked") ||
        this.has(value, "disabled")
      );
    },
    keyToggle(event) {
      // the key event happens whether the control is disabled or not
      // nothing should be done if disabled is true
      if (this.disabled) {
        return;
      }
      this.toggle(event);
    },
    toggle(event) {
      const toggled = !this.toggled;
      if (!this.sync) {
        this.toggled = toggled;
      }
      this.$emit("input", toggled);
      this.$emit("change", {
        value: toggled,
        tag: this.tag,
        srcEvent: event
      });
    }
  }
};
</script>

<style lang="scss" scoped>
label {
  margin-bottom: 0;
}
.switch {
  display: inline-block;
  position: relative;
  vertical-align: middle;
  user-select: none;
  font-size: 10px;
  cursor: pointer;
  .v-switch-input {
    opacity: 0;
    position: absolute;
    width: 1px;
    height: 1px;
  }
  .v-switch-label {
    position: absolute;
    top: 0;
    font-weight: 600;
    color: white;
    z-index: 1;
    &.v-left {
      left: 8px;
    }
    &.v-right {
      right: 8px;
    }
  }
  .v-switch-core {
    display: block;
    position: relative;
    box-sizing: border-box;
    outline: 0;
    margin: 0;
    transition: border-color 0.3s, background-color 0.3s;
    user-select: none;
    .v-switch-button {
      display: block;
      position: absolute;
      overflow: hidden;
      top: 0;
      left: 0;
      border-radius: 100%;
      background-color: #fff;
      z-index: 2;
    }
  }
  &.disabled {
    pointer-events: none;
    opacity: 0.6;
  }
}
</style>
